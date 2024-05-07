<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Like;
use App\Models\Comment;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index(Request $request)
    {
        return redirect()->route("feed");
    }

    function feed()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $friendships = $user->friendships()->pluck('friend_id');
        $posts = Post::whereIn('user_id', $friendships)->orderBy("created_at", "desc")->paginate(10);
        return view("feed.feed", ["title" => "Лента", "posts" => $posts]);
    }

    public function likePost(Request $request, $postId)
    {
        $userId = Auth::id();
        $like = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if (!$like) {
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
        }

        return redirect()->back();
    }

    public function unlikePost(Request $request, $postId)
    {
        $userId = Auth::id();
        $like = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($like) {
            $like->delete();
        }

        return redirect()->back();
    }

    public function toggleLike(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        // Проверяем, лайкнул ли пользователь пост
        if ($post->isLikedBy($user)) {
            // Если пользователь уже лайкнул пост, удаляем лайк
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            // Если пользователь еще не лайкнул пост, добавляем лайк
            $like = new Like();
            $like->user_id = $user->id;
            $post->likes()->save($like);
        }

        return redirect()->back();
    }


}
