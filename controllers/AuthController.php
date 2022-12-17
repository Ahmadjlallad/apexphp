<?php

namespace Apex\controllers;

use Apex\src\Controller\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->view('login');
    }
    public function storeLogin()
    {
        return $this->view('login');
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