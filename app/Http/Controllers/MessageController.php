<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    function messages()
    {
        // Получаем текущего пользователя
        $currentUserId = \auth()->id();

        // Получаем список уникальных пользователей, с которыми текущий пользователь взаимодействовал
        $dialogUsers = User::whereHas('sentMessages', function ($query) use ($currentUserId) {
            $query->where('receiver_id', $currentUserId);
        })
            ->orWhereHas('receivedMessages', function ($query) use ($currentUserId) {
                $query->where('sender_id', $currentUserId);
            })
            ->get();

        // Для каждого пользователя получаем последнее сообщение в диалоге
        $dialogs = collect([]);
        foreach ($dialogUsers as $user) {
            $lastMessage = Message::where(function ($query) use ($currentUserId, $user) {
                $query->where('sender_id', $currentUserId)
                    ->where('receiver_id', $user->id);
            })
                ->orWhere(function ($query) use ($currentUserId, $user) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $currentUserId);
                })
                ->latest()
                ->first();

            if ($lastMessage) {
                $dialogs->push([
                    'user' => $user,
                    'lastMessage' => $lastMessage,
                ]);
            }
        }
        $dialogs = $dialogs->sortByDesc(function ($item) {
            return $item['lastMessage']->created_at;
        });
        return view('messages.messages', ["title"=>"Сообщения"], compact('dialogs'));
    }

    function dialogue($login)
    {
        $currentUser = Auth::user();
        $otherUser = User::where("name", $login)->first();

        $messages = Message::where(function ($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $otherUser->id);
        })
            ->orWhere(function ($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                    ->where('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view("messages.dialogue", compact('messages', 'otherUser'), ["title"=>"Диалог с ".$otherUser->name]);
    }

    public function send_message(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $currentUserId = \auth()->id();
        $is_subscribed_back = DB::table("friendships")
            ->where("user_id", $request->receiver_id)
            ->where("friend_id", $currentUserId)
            ->exists();
        $is_subscribed = DB::table("friendships")
            ->where("user_id", $currentUserId)
            ->where("friend_id", $request->receiver_id)
            ->exists();
        if(!$is_subscribed || !$is_subscribed_back){
            return redirect()->back()->with("error", "Вы не можете отправлять сообщения этому пользователю.");
        }
        // Создаем новое сообщение
        $message = new Message();
        $message->content = $request->input('content');
        $message->sender_id = $currentUserId;
        $message->receiver_id = $request->input('receiver_id');
        $message->save();

        // Далее вы можете добавить какую-либо логику, например, редирект обратно на страницу диалога
        return redirect()->back()->with('success', 'Сообщение успешно отправлено!');
    }

    public function delete_message($message_id)
    {
        DB::table("messages")->where("id", $message_id)->delete();
        return redirect()->back();
    }
}
