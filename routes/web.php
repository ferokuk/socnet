<?php

use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [FeedController::class, "index"])->name("index");

// Маршруты для регистрации
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);

// Маршруты для авторизации
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get("/feed", [FeedController::class, "feed"])->name("feed");

    Route::delete('/post/delete/{id}', [PostController::class, 'delete'])->name('post.delete');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/update/{id}', [PostController::class, 'update'])->name('post.update');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');


    Route::post('/like/{postId}', [FeedController::class, 'likePost'])->name('like');
    Route::delete('/unlike/{postId}', [FeedController::class, 'unlikePost'])->name('unlike');
    Route::post('/toggle-like/{postId}', [FeedController::class, 'toggleLike'])->name('toggleLike');
    Route::get('/post/{id}/comments', [PostController::class, 'comments'])->name('post.comments');
    Route::post('/post/{id}/comment/store', [PostController::class, 'storeComment'])->name('comment.store');
    Route::delete('post/{postId}/comment/{commentId}', [PostController::class, 'deleteComment'])->name('comment.delete');

    Route::prefix("profile")->group(function () {
        Route::get("/", [UserController::class, "current_user_profile"])
            ->name("me");

        Route::get("{login}", [UserController::class, "profile"])
            ->name("profile")
            ->where("login", "[a-zA-Z_0-9]+");

        Route::post('friend/add/{user_id}', [FriendController::class, 'add'])
            ->name('friend.add')
            ->where("user_id", "[0-9]+");

        Route::delete('friends/delete/{friend_id}', [FriendController::class, "delete"])
            ->name('friend.delete');

        Route::delete('friends/delete/{sub_id}', [FriendController::class, "delete_sub"])
            ->name('friend.delete_sub');

    });
        Route::get("edit", [UserController::class, "edit_page"])
            ->name("profile.edit");

        Route::put("edit/change", [UserController::class, "edit_change"])
            ->name("profile.edit.update");
        Route::get("/following", [FriendController::class, "my_friends"])
            ->name("my_friends");

        Route::get("/subscribers", [FriendController::class, "my_subs"])
            ->name("my_subs");

    Route::get("messages", [\App\Http\Controllers\MessageController::class, "messages"])
        ->name("messages");

    Route::get("messages/{login}", [\App\Http\Controllers\MessageController::class, "dialogue"])
        ->where("login", "[a-zA-Z_0-9]+")
        ->name("dialogue");

    Route::post('/message/send', [\App\Http\Controllers\MessageController::class, "send_message"])
        ->name('message.send');

    Route::delete("/message/delete/{message_id}", [\App\Http\Controllers\MessageController::class, "delete_message"])
        ->name("message.delete");

    Route::post('/profile/image/update/{imageName}', [UserController::class, 'updateProfileImage'])->name('profile.image.update');

    Route::get("/activity", [FriendController::class, "activity"])
        ->name("activity");

    Route::get("/search", [FriendController::class, "search"])
        ->name("search");
});
