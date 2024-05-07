@extends('layout')

@section('content')
    <div class="container">
        <h2 class="mb-4">Изменить профиль</h2>
        <div class="card">
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('profile.edit.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="username" class="form-label">Никнейм</label>
                        <input type="text" maxlength="50" class="form-control" id="username" name="name"
                               value="{{ auth()->user()->name }}">
                    </div>

                    <div class="mb-3">
                        <label for="firstname" class="form-label">Имя</label>
                        <input type="text" maxlength="50" class="form-control" id="firstname" name="first_name"
                               value="{{ auth()->user()->first_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Фамилия</label>
                        <input type="text" maxlength="50" class="form-control" id="lastname" name="last_name"
                               value="{{ auth()->user()->last_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" maxlength="255" class="form-control" id="email" name="email"
                               value="{{ auth()->user()->email }}">
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Пол</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="male"
                                   value="m" {{ old('gender', auth()->user()->gender) == 'm' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">
                                Мужской
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="female"
                                   value="f" {{ old('gender', auth()->user()->gender) == 'f' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">
                                Женский
                            </label>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="show_info"
                               name="show_personal_info" {{ old('show_personal_info', auth()->user()->show_personal_info) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_info">Показывать личную информацию(имя, фамилия,
                            пол)</label>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Пароль (ещё раз)</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation">
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
        </div>
    </div>

@endsection
