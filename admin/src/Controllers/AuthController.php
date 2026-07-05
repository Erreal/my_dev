<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->layout = 'login';
    }

    /**
     * Show login form.
     */
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/admin/dashboard');
        }

        $this->display('auth/login', [
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /**
     * Handle login submission.
     */
    public function login(): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/login');
        }

        $username = $this->request->post('username', '');
        $password = $this->request->post('password', '');

        if (empty($username) || empty($password)) {
            Session::setFlash('error', 'Пожалуйста, заполните все поля.');
            $this->redirect('/admin/login');
        }

        if (Auth::attempt($username, $password)) {
            Session::setFlash('success', 'Добро пожаловать, ' . $username . '!');
            $this->redirect('/admin/dashboard');
        }

        Session::setFlash('error', 'Неверное имя пользователя или пароль.');
        $this->redirect('/admin/login');
    }

    /**
     * Handle logout.
     */
    public function logout(): void
    {
        Auth::logout();
        Session::setFlash('success', 'Вы вышли из системы.');
        $this->redirect('/admin/login');
    }
}