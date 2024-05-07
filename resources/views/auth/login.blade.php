@extends("layout")

@section("content")
    <body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
    @if($errors->any())
        <div style="color: red;">
            {{ implode('', $errors->all(':message')) }}
        </div>
    @endif
                <div class="card">
                    <div class="card-header">Вход в социальную сеть</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Запомнить меня</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Войти</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection

