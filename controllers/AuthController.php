<?php

namespace Apex\controllers;

use Apex\src\Controller\Controller;
use Symfony\Component\VarDumper\VarDumper;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->view('login');
    }

    public function storeLogin(): bool|string
    {
        $validate = $this->request->validate($this->request->postParams(), ['password' => 'required|min:6', 'email' => 'required|email']);
        return $this->view('login', ['errors' => $validate->errors()]);
    }

    public function showRegister(): bool|string
    {
        return $this->view('register');
    }
    public function storeRegister(): bool|string
    {
        return $this->view('register');
    }
}