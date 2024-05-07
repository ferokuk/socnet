@extends("layout")

@section("content")
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Посты</div>

                    <div class="card-body">
                        <div class="ms-3">{{ $posts->links("vendor.pagination.views.bootstrap-5") }}</div>
                        @if ($posts->count() > 0)
                            @foreach ($posts as $post)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p class="card-text">
                                            <a href="{{route("profile", $post->user->name)}}"
                                               class="text-decoration-none">
                                                <img src="{{asset("storage/images/".$post->user->image)}}"
                                                     alt="Avatar"
                                                     class="rounded-circle me-2 cursor-pointer"
                                                     style="width: 50px; height: 50px;">
                                            </a>
                                            <a href="{{route("profile", $post->user->name)}}"
                                               class="text-decoration-none">{{ $post->user->name }}</a>
                                        </p>
                                        <p class="card-text">{!! $post->content !!}</p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Создано: {{ $post->created_at->format('d.m.Y H:i') }}
                                                @if ($post->created_at != $post->updated_at)
                                                    <br>Обновлено: {{ $post->updated_at->format('d.m.Y H:i') }}
                                                @endif
                                            </small>
                                        </p>
                                        <!-- Кнопка "редактировать" для автора поста -->
                                        @if(Auth::check() && $post->user_id == Auth::user()->id)
                                            <a href="{{ route('post.edit', $post->id) }}"
                                               class="btn btn-sm btn-primary">Редактировать</a>
                                        @endif
                                        <!-- Ссылка на страницу с комментариями -->
                                        <!-- Форма для лайка и отображения количества лайков -->
                                        <form action="{{ route('toggleLike', $post->id) }}" method="POST" class="mb-2">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-{{ $post->isLikedBy(Auth::user()) ? 'danger' : 'light' }} like-btn rounded-circle">
                                                {{ $post->isLikedBy(Auth::user()) ? '❤️' : '🤍' }}
                                            </button>
                                            <a href="{{ route('post.comments', $post->id) }}"
                                               class="btn btn-sm btn-secondary">Комментарии</a>
                                            <span class="ms-2 mt-3">{{ $post->likes->count() }} <i class="fas fa-heart text-danger"></i></span>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            {{ $posts->links("vendor.pagination.views.bootstrap-5") }}
                        @else
                            <p>Постов нет.</p>
                        @endif
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
