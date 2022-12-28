<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\Controller\Controller;
use Apex\src\Request;

class AuthController extends Controller
{
    public function showLogin(string|null $i)
    {
        return $this->view('login');
    }

    public function storeLogin(): bool|string
    {
        return $this->view('login');
    }

    public function register(Request $request): bool|string
    {
        $user = User::create(['name' => 'ahmad']);
        dd($user->save());
        $errors = null;
        if ($this->request->isPost()) {
            $user->fill($this->request->input());
            $validate = $this->request->validate($this->request->input(), ['password' => 'required|min:6', 'email' => 'required|email']);
            $errors = $validate->errors();

        }
        return $this->view('register', ['user' => $user, 'errors' => $errors]);
    }
}