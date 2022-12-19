<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\Controller\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->view('login');
    }

    public function storeLogin(): bool|string
    {
        return $this->view('login');
    }

    public function register(): bool|string
    {
        $user = User::create();
        $errors = null;
        if ($this->request->isPost()) {
            $user->fill($this->request->input());
            $validate = $this->request->validate($this->request->input(), ['password' => 'required|min:6', 'email' => 'required|email']);
            $errors = $validate->errors();

        }
        return $this->view('register', ['user' => $user, 'errors' => $errors]);
    }
}