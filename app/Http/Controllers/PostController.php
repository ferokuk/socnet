<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PostController extends Controller
{
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id != auth()->id()) {
            return redirect()->back()->with("error", "У вас нет прав для удаления этого поста.");
        }
        // Удаление комментариев и лайков, связанных с постом
        DB::table('comments')->where('post_id', $id)->delete();
        DB::table('likes')->where('post_id', $id)->delete();
        // Удаление поста
        $post->delete();

        return redirect()->back()->with('success', 'Пост и все комментарии успешно удалены.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'У вас нет прав для редактирования этого поста.');
        }
        return view('posts.editor', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Проверяем, является ли текущий пользователь автором поста
        if ($post->user_id !== auth()->id()) {
            return redirect()->back();
        }

        $post->content = $request->input('post_content');
        $post->save();

        return redirect()->route('me')->with('success', 'Пост успешно отредактирован.');
    }

    public function store(Request $request)
    {
        // Проверяем, является ли пользователь авторизованным
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Для создания поста необходимо войти в систему.');
        }

        // Валидация данных
        $request->validate([
            'post_content' => 'required|string',
        ]);

        // Создаем новый пост
        $post = new Post();
        $post->user_id = auth()->id();
        $post->content = $request->input('post_content');
        $post->save();

        return redirect()->route('me')->with('success', 'Пост успешно создан.');
    }

    public function create()
    {
        return view('posts.editor');
    }

    public function comments($postId)
    {
        $post = Post::findOrFail($postId);
        $comments = Comment::where('post_id', $postId)->orderBy('created_at', 'desc')->get();

        return view('posts.comments', compact('post', 'comments'));
    }

    public function storeComment(Request $request, $postId)
    {
        // Проверка валидации данных
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        // Создание нового комментария
        $comment = new Comment();
        $comment->user_id = auth()->id(); // ID текущего пользователя
        $comment->post_id = $postId;
        $comment->content = $validatedData['content'];
        $comment->save();

        // Возвращаем пользователя обратно на страницу поста
        return redirect()->route('post.comments', $postId)->with('success', 'Комментарий успешно добавлен.');
    }

    public function deleteComment($postId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Проверяем, имеет ли пользователь право удалить комментарий
        if ($comment->user_id != auth()->id() && $comment->post->user_id != auth()->id()) {
            return redirect()->back()->with('error', 'У вас нет прав для удаления этого комментария.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Комментарий успешно удален.');
    }
}
