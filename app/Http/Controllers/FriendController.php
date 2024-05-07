<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add($user_id)
    {
        if ($user_id == \auth()->id()) {
            return redirect()->back();
        }
        DB::table("friendships")
            ->insert([
                "user_id" => auth()->user()["id"],
                "friend_id" => $user_id,
            ]);


        return back()->with('message', 'Вы успешно подписались.');
    }

    public function all_friends($login)
    {
        //$friends = auth()->user()->friends();
        $user = User::where("name", $login)->first();
        $friends = $user->friendships()->join("users", "users.id", "=", "friendships.friend_id")->get();
        return view("users.friends", ["title" => "Друзья", "friends" => $friends, "login" => $login]);
    }

    public function my_friends()
    {
        $user = User::find(\auth()->id());
        $friends = $user->friendships()->join("users", "users.id", "=", "friendships.friend_id")->paginate(10);
        return view("users.friends", ["title" => "Подписки",
            "friends" => $friends, "login" => $user->name, "type"=>"FOLL"]);
    }

    public function my_subs()
    {
        $user = \auth()->user();
        $subs = Friendship::where("friend_id", $user->id)
            ->join("users", "users.id", "=", "friendships.user_id")
            ->paginate(100);
        return view("users.friends", ["title" => "Подписчики",
            "friends" => $subs, "login" => $user->name, "type"=>"SUBS"]);
    }

    public function delete($friend_id)
    {
        DB::table("friendships")
            ->where("user_id", auth()->id())
            ->where("friend_id", $friend_id)
            ->delete();
        return back()->with("message", "Вы успешно отписались от ".User::find($friend_id)->name.".");
    }

    public function delete_sub($sub_id)
    {
        DB::table("friendships")
            ->where("user_id", $sub_id)
            ->where("friend_id", auth()->id())
            ->delete();

        return back()->with("message", "Вы успешно убрали ".User::find($sub_id)->name." из подписчиков.");
    }

    public function activity()
    {
        $user_id = \auth()->id();

        // Получаем лайки пользователя, относящиеся к постам, и сортируем их по дате создания
        $likes = Like::whereHas('post', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->whereNot("likes.user_id", $user_id)->orderBy('created_at', 'desc')->paginate(100);
        return view('users.activity', ['likes' => $likes]);
    }

    public function search(Request $request)
    {
        if(!$request->has("q"))
        {
            return view("users.search");
        }
        $searchTerm = $request->input('q');

        $users = User::where('name', 'like', "%$searchTerm%")->paginate(20)->appends(["q" => $searchTerm]);

        return view('users.search', ['users' => $users, "query"=>$searchTerm]);
    }
}
