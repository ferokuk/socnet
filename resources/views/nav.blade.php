<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand me-5" href="{{ url('/') }}"><i class="fas fa-cat text-white"></i>socnet</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Войти
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Регистрация
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('me') }}">
                            <i class="fas fa-user"></i> Профиль
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-newspaper"></i> Лента
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("messages") }}">
                            <i class="fas fa-envelope"></i> Сообщения
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("my_friends") }}">
                            <i class="fas fa-users"></i> Подписки
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("my_subs") }}">
                            <i class="fas fa-user-friends"></i> Подписчики
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("activity") }}">
                            <i class="fas fa-chart-line"></i> Активность
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route("search") }}">
                            <i class="fas fa-search"></i> Поиск
                        </a>
                    </li>
                @endguest
            </ul>
            @auth
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('storage/images/'.Auth::user()->image) }}" alt="Аватар"
                                 style="width: 30px; height: 30px; border-radius: 50%;">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('me') }}">
                                    Профиль
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Настройки профиля
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Выйти
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <form method="POST" action="{{ route('logout') }}" class="d-none" id="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-link">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('navbarDropdown').addEventListener('click', function (event) {
            event.preventDefault();
            document.getElementById('navbarDropdown').classList.toggle('show');
            var menu = document.querySelector('#navbarDropdown + .dropdown-menu');
            menu.classList.toggle('show');
        });
    });
</script>
