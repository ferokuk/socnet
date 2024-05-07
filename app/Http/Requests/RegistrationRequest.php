<?php

namespace App\Http\Requests;

use App\Rules\ValidateLogin;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:users', new ValidateLogin],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Этот логин уже занят',
            'name.required' => 'Логин является обязательным полем',
            'email.required' => 'Email является обязательным полем',
            'email.email' => 'Введите правильный email',
            'email.unique' => 'Такой email уже зарегистрирован',
            'password.required' => 'Пароль является обязательным полем',
            'password.min' => 'Минимальная длина пароля составляет 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}
