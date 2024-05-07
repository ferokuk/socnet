<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

// Добавим модель Post
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route("me");
        }
        return view('auth.register', ["title" => "Регистрация"]);
    }


    public function register(RegistrationRequest $request)
    {

        // Создание пользователя
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route("login", ["title" => "Авторизация"]); // Перенаправление на вашу дашборд страницу
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route("me");
        }
        return view('auth.login', ["title" => "Авторизация"]);
    }


    public function login(Request $request)
    {
        // Валидация данных
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        // Попытка входа
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route("profile", ["login" => auth()->user()->name]); // Исправлено: auth()->user()["name"] на auth()->user()->name
        }
        return back()->withErrors(['email' => 'Неверный email или пароль']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route("login");
    }

    public function current_user_profile()
    {
        $user = User::findOrFail(auth()->id()); // Исправлено: использование модели User для получения пользователя
        $friend_info = Auth::user()->isFriendWith($user->id);
        $posts = Post::where("user_id", auth()->id())->paginate(10); // Исправлено: использование модели Post для получения постов пользователя
        return view('users.profile', [
            "user" => $user,
            "title" => $user ? "Профиль " . $user->name : "Пользователь не найден",
            "friend_status" => $friend_info,
            "posts" => $posts,
            "subscribers" => Friendship::where("friend_id", \auth()->id())->count()
        ]);

    }


    public function profile($login)
    {
        app()->setLocale('ru');
        if ($login == auth()->user()->name) { // Исправлено: auth()->user()["login"] на auth()->user()->name
            return redirect()->route("me");
        }
        $user = User::where("name", $login)->firstOrFail(); // Исправлено: использование модели User для получения пользователя
        $posts = Post::where("user_id", $user->id)->orderBy('created_at', 'desc')->paginate(10);
        $friend_info = Auth::user()->isFriendWith($user->id);
        $is_subscribed_back = DB::table("friendships")
            ->where("user_id", $user->id)
            ->where("friend_id", auth()->id())
            ->exists();
        return view('users.profile', [
            "user" => $user,
            "title" => $user ? "Профиль " . $user->name : "Пользователь не найден",
            "friend_status" => $friend_info,
            "posts" => $posts,
            "is_subscribed_back" => $is_subscribed_back,
            "subscribers" => Friendship::where("friend_id", $user->id)->count()
        ]);
    }

    public function updateProfileImage(Request $request, $imageName)
    {
        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            // Удаляем старое изображение, если оно существует
            if ($user->image) {
                Storage::delete('images/' . $user->image);
            }

            // Загружаем новое изображение
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);

            // Обновляем поле image в базе данных
            $user->image = $imageName;
            $user->save();
        }

        return back()->with('success', 'Фотография профиля успешно обновлена.');
    }

    public function edit_page()
    {
        return view("users.edit", ["user" => \auth()->user()]);
    }

    public function edit_change(Request $request)
    {
        $user = User::find(\auth()->id());

        // Проверка наличия входящих данных
        $request->validate([
            'name' => 'required|regex:/^[a-zA-Z0-9_]+$/u|string|max:50',
            'first_name' => 'required|string|alpha|max:50',
            'last_name' => 'required|string|alpha|max:50',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'gender' => 'nullable|in:m,f',
        ], [
            'name.required' => 'Пожалуйста, укажите имя пользователя.',
            'name.regex' => "Никнейм должен содержать только буквы, цифры и символ _",
            'name.max' => 'Имя пользователя не должно превышать 50 символов.',
            'first_name.required' => 'Пожалуйста, укажите имя.',
            'first_name.alpha'=> "Имя должно содержать только буквы",
            'first_name.max' => 'Имя не должно превышать 50 символов.',
            'last_name.required' => 'Пожалуйста, укажите фамилию.',
            'last_name.alpha'=> "Фамилия должно содержать только буквы",
            'last_name.max' => 'Фамилия не должна превышать 50 символов.',
            'email.required' => 'Пожалуйста, укажите email.',
            'email.email' => 'Пожалуйста, введите корректный email адрес.',
            'email.max' => 'Email не должен превышать 255 символов.',
            'email.unique' => 'Указанный email уже используется.',
            'gender.in' => 'Недопустимое значение для поля пола.',
            'password.string' => 'Поле "Пароль" должно быть строкой.',
            'password.min' => 'Поле "Пароль" должно содержать не менее :min символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->show_personal_info = $request->has("show_personal_info");
        // Обновление пароля, если он был введен
        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }
        $user->save();
        return redirect()->back()->with('success', 'Профиль успешно обновлен.');
    }

}
