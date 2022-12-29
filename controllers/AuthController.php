<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\Controller\Controller;
use Apex\src\Request;

class AuthController extends Controller
{
    public function showLogin(): bool|string
    {
        return $this->view('login');
    }

    public function storeLogin(): bool|string
    {
        return $this->view('login');
    }

    public function register(Request $request): bool|string
    {
        $user = User::create();
        if ($this->request->isPost()) {
            $user->fill($this->request->input());
            $user->save();
            $validate = $this->request->validate($this->request->input(), ['password' => 'required|min:6', 'email' => 'required|email', 'confirm_password'=> 'required|same:password']);
            $user->errorMessage->margeErrorBadges($validate->errors());
        }
        return $this->view('register', ['user' => $user]);
    }
}