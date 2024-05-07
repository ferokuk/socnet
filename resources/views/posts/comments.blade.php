@extends("layout")

@section("content")
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Комментарии к посту</div>

                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text">
                                <a href="{{ route('profile', $post->user->name) }}" class="text-decoration-none">
                                    <img src="{{ asset("storage/images/".$post->user->image) }}" alt="Avatar"
                                    class="rounded-circle me-2 cursor-pointer" style="width: 50px; height: 50px;">
                                </a>
                                <a href="{{ route('profile', $post->user->name) }}" class="text-decoration-none">{{
                                    $post->user->name }}</a>
                            </p>
                            <p class="card-text">{!! $post->content !!}</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Создано: {{ $post->created_at->format('d.m.Y H:i') }}
                                    <form action="{{ route('toggleLike', $post->id) }}" method="POST" class="mb-2">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $post->isLikedBy(Auth::user()) ? 'danger' : 'light' }} like-btn rounded-circle">
                                            {{ $post->isLikedBy(Auth::user()) ? '❤️' : '🤍' }}
                                        </button>
                                        <span class="ms-2 mt-3">{{ $post->likes->count() }} ❤</span>
                                    </form>
                                    @if ($post->created_at != $post->updated_at)
                                    <br>Обновлено: {{ $post->updated_at->format('d.m.Y H:i') }}
                                    @endif
                                </small>
                            </p>
                        </div>
                    </div>

                    <h5>Комментарии:</h5>

                    @if ($comments->count() > 0)
                    @foreach ($comments as $comment)
<div class="card mb-3">
    <div class="card-body">
        <!-- Добавляем кнопку "удалить" только если текущий пользователь является автором комментария или автором поста -->
        @if(auth()->id() == $comment->user_id || auth()->id() == $post->user_id)
        <form action="{{ route('comment.delete', ['postId' => $post->id, 'commentId' => $comment->id]) }}" method="POST" class="float-end">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
        </form>
        @endif
        <p class="card-text">
            <a href="{{ route('profile', $comment->user->name) }}" class="text-decoration-none">
                <img src="{{ asset("storage/images/".$comment->user->image) }}" alt="Avatar" class="rounded-circle me-2 cursor-pointer" style="width: 50px; height: 50px;">
            </a>
            <a href="{{ route('profile', $comment->user->name) }}" class="text-decoration-none">{{ $comment->user->name }}</a>
        </p>
        <p class="card-text">{!! $comment->content !!}</p>
        <p class="card-text">
            <small class="text-muted">Опубликовано: {{ $comment->created_at->format('d.m.Y H:i') }}</small>
        </p>
    </div>
</div>
@endforeach
                    @else
                    <p>Комментариев пока нет.</p>
                    @endif

                    <!-- Форма для добавления нового комментария -->
                    <form action="{{ route('comment.store', $post->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Новый комментарий</label>
                            <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Добавить комментарий</button>
                    </form>

                    <!-- Форма для лайка и отображения количества лайков -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .like-btn {
        width: 50px;
        height: 50px;
    }
</style>