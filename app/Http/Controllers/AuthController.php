<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()//Показ формы ввода
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([//Проверка введенных данных
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Login attempt', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {//Проверка данных и авторизация
            \Log::info('Login successful', ['user_id' => Auth::id()]);
            return redirect()->intended('/stats');//Перевод пользователя туда, куда он хотел попасть до авторизации, если страницы нет, то на /stats
        }
        \Log::warning('Login failed', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Invalid credentials']);//Если данные не верные, то возвращаемся назад с ошибкой
    }

    public function logout()
    {
        Auth::logout();//Выход из системы

        return redirect('/login');//Перевод на страницу входа
    }
}
