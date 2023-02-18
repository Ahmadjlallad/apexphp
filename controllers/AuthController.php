<?php

namespace Apex\controllers;

use Apex\models\User;
use Apex\src\App;
use Apex\src\Controller\Controller;
use Apex\src\Request;
use Apex\src\Response;
use Rakit\Validation\RuleNotFoundException;

class AuthController extends Controller
{
    public function showLogin(): Response
    {
        return $this->view('auth.login');
    }

    public function storeLogin(Request $request): Response
    {
        if ($request->sessionValidate($request->input(), ['email' => ['required', 'email'], 'password' => 'required'])) {
            $user = new User($request->input());
            if ($user->login()) {
                App::getInstance()->session->remove('user');
                return $this->response->redirect('/');
            }
            return $this->response->back()->with('user-error', ['attr' => $user->getAttributes(), 'error' => $user->errorBag]);
        }
        return $this->response->back();
    }

    public function showRegister(Request $request, Response $response): Response
    {
        return $this->view('auth.register');
    }

    /**
     * @throws RuleNotFoundException
     */
    public function storeRegister(Request $request): Response
    {
        $user = User::create($this->request->input());
        $validate = $request->sessionValidate($this->request->input(), [
            'password' => 'required|min:6',
            'email' => [
                'required',
                'email',
                validator('unique')->model(User::class)->column('email')
            ],
            'confirm_password' => 'required|same:password',
            'birth_date' => 'required|date'
        ]);
        if ($validate && $user->save() && $user->login()) {
            App::getInstance()->session->setFlash('success', 'test');
            return $this->response->redirect('/');
        }
        return $this->response->redirect('auth.register')->with('register-errors', $user->errorBag->all());
    }

    public function logout(Response $response): Response
    {
        auth()->logout();
        return $response->redirect('/');
    }

    public function profile(Response $response): Response
    {
        return $this->view('profile');
    }
}