@extends("layout")

@section("content")
    <div class="container-fluid">
        <form class="d-flex me-5" action="{{ route('search') }}" method="GET">
            <input class="form-control me-2" type="search" name="q" placeholder="Искать пользователей..."
                   aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Поиск</button>
        </form>
        @if (isset($users) and $users->count() == 0)
            <p>Пользователи не найдены.</p>
        @elseif(isset($users))
            <div class="ms-3">{{ $users->links("vendor.pagination.views.bootstrap-5") }}</div>
            <ul class="list-group">
                @foreach ($users as $user)
                    <li class="list-group-item d-flex align-items-center">
                        <a href="{{ route('profile', $user->name) }}">
                            <img src="storage/images/{{ $user->image }}" alt="{{ $user->name }}"
                                 class="rounded-circle me-3" width="50"
                                 height="50">
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ route('profile', $user->name) }}"
                               class="text-decoration-none">{{ $user->name }}</a>
                        </div>
                        @if (auth()->user()->isFriendWith($user->id) == "accepted")
                            <form action="{{ route('friend.delete', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Отписаться</button>
                            </form>

                        @else
                            <form action="{{ route('friend.add', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Подписаться</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
