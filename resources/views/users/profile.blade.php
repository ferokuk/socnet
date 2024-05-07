@extends("layout")

@section("content")
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Профиль пользователя</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-picture">
                                    <img src="{{ asset('storage/images/'.$user->image) }}" alt="Avatar"
                                         class="rounded-circle cursor-pointer" width="200" height="200" id="profile-pic"
                                         onclick="document.getElementById('pic-input').click()">
                                </div>
                                <form action="{{ route('profile.image.update', $user->image) }}" method="POST"
                                      enctype="multipart/form-data" style="display: none;">
                                    @csrf
                                    @method('POST')
                                    <input type="file" id="pic-input" onchange="updateProfilePicture()"
                                           name="profile_image">
                                    <button type="submit" id="submit-btn" style="display: none;">Сменить фото</button>
                                </form>
                            </div>
                            <div class="col-md-8">
                                @if($user)
                                    @if($user->show_personal_info or $user->id == auth()->id())
                                        <h2>{{ $user->name }}</h2>
                                        <h5 class="text-muted">{{$user->last_name}} {{$user->first_name}}</h5>
                                        @if($user->gender == "f")
                                        <h5 class="text-muted">Пол: женский</h5>
                                        @elseif($user->gender == "m")
                                        <h5 class="text-muted">Пол: мужской</h5>
                                        @else
                                        <h5 class="text-muted">Пол: не указан</h5>
                                        @endif
                                    @endif
                                    @if($user->id == auth()->id())
                                        <p>Email: {{ $user->email }}</p>
                                    @endif

                                    @php
                                        app()->setLocale('ru');
                                        $dateString = $user->created_at;
                                        $formattedDate = \Carbon\Carbon::parse($dateString)
                                        ->locale('ru')->isoFormat('D MMMM YYYY HH:mm');
                                    @endphp
                                    <p>Дата регистрации: {{ $formattedDate }}</p>
                                    @php
                                        function plural(int $count, string $form1, string $form2, string $form5): string
                                        {
                                            $count = abs($count) % 100;
                                            $lcount = $count % 10;
                                            if ($count >= 11 && $count <= 19) {
                                                return $form5;
                                            }
                                            if ($lcount >= 2 && $lcount <= 4) {
                                                return $form2;
                                            }
                                            if ($lcount == 1) {
                                                return $form1;
                                            }
                                            return $form5;
                                        }

                                        // Отображение количества подписчиков с правильной формой слова "подписчик"
                                        $subscriberWord = plural($subscribers, 'подписчик', 'подписчика', 'подписчиков');
                                    @endphp
                                    <p class="text-muted">{{$subscribers}} {{$subscriberWord}}</p>
                                    @if(Auth::user()->id !== $user->id)
                                        @if($is_subscribed_back)
                                            <p>Этот пользователь подписан на вас</p>
                                        @endif
                                        @if($friend_status == "accepted")
                                            <p class="text-success">Вы подписаны</p>
                                            <form action="{{ route('friend.delete', $user->id) }}" method="POST"
                                                  class="float-right">
                                                @csrf
                                                @method('DELETE')
                                                @if($is_subscribed_back)
                                                    <a href="{{route("dialogue", $user->name)}}"
                                                       class="btn btn-primary ">Сообщение</a>
                                                @endif
                                                <button type="submit" class="btn btn-danger">Отписаться</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('friend.add', $user->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Подписаться</button>
                                            </form>
                                            @if(session('message'))
                                                <div class="alert alert-success">
                                                    {{ session('message') }}
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    <h2>Упс, такого пользователя не существует!</h2>
                                @endif

                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('post.create') }}" class="btn btn-success">Создать пост</a>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Редактировать
                                        профиль</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Посты пользователя</div>
                    <div class="card-body">
                        @if ($posts->count() > 0)
                            {{ $posts->links("vendor.pagination.views.bootstrap-5") }}
                            <ul class="list-group">
                                @foreach ($posts->reverse() as $post)
                                    <div class="text-muted">
                                        {{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y H:i') }}
                                    </div>
                                    @if ($post->created_at != $post->updated_at)
                                        <div class="text-muted">
                                            Обновлено: {{ \Carbon\Carbon::parse($post->updated_at)->format('d.m.Y H:i') }}
                                        </div>
                                    @endif
                                    <li class="list-group-item">
                                        {!! $post->content !!}
                                        @if(Auth::id() === $post->user_id)
                                            <form action="{{ route('post.delete', $post->id) }}" method="POST"
                                                  class="float-right">
                                                <a href="{{ route('post.edit', $post->id) }}"
                                                   class="btn btn-primary btn-sm">Редактировать</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                            </form>
                                        @endif
                                        <!-- Кнопка "комментарии" для всех постов -->

                                        <!-- Форма для лайка и отображения количества лайков -->
                                        <form action="{{ route('toggleLike', $post->id) }}" method="POST" class="mb-2">
                                            @csrf
                                            <button type="submit" style="width: 50px; height: 50px"
                                                    class="btn btn-{{ $post->isLikedBy(Auth::user()) ? 'danger' : 'light' }} like-btn rounded-circle">
                                                {{ $post->isLikedBy(Auth::user()) ? '❤️' : '🤍' }}
                                            </button>
                                            <a href="{{ route('post.comments', $post->id) }}"
                                               class="btn btn-sm btn-secondary">Комментарии</a>
                                            <span class="ms-2 mt-3">{{ $post->likes->count() }} <i
                                                    class="fas fa-heart text-danger"></i></span>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                            {{ $posts->links("vendor.pagination.views.bootstrap-5") }}
                        @else
                            <p>У этого пользователя нет постов.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        const pic = document.getElementById('profile-pic');
        const picInput = document.getElementById('pic-input');
        const submitBtn = document.getElementById('submit-btn');

        function updateProfilePicture() {
            const [file] = picInput.files
            const imageFormats = ['png', 'jpg', 'jpeg'];

            if (file && imageFormats.includes(file.name.split('.').pop())) {
                pic.src = URL.createObjectURL(file);
                submitBtn.click();
            }
        }
    </script>
@endsection
<style>
    .cursor-pointer:hover {
        cursor: pointer;
    }
</style>
