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
        $user = new User;
        if (!empty($attr = App::getInstance()->session->getFlash('user'))) {
            $user->fill($attr['attr']);
            $user->errorBag->margeErrorBadges($attr['error']);
        }
        return $this->view('login', ['user' => $user]);
    }

    public function storeLogin(Request $request): Response
    {
        $validation = $request->validate($request->input(), ['email' => ['required', 'email'], 'password' => 'required']);
        $user = new User($request->input());
        if (!$validation->fails() && $user->login()) {
            App::getInstance()->session->remove('user');
            return $this->response->redirect('/');
        }
        App::getInstance()->session->setFlash('user', ['attr' => $user->getAttributes(), 'error' => $user->errorBag]);
        return $this->response->back();
    }

    /**
     * @throws RuleNotFoundException
     */
    public function register(Request $request): Response
    {
        $user = User::create();
        if ($this->request->isPost()) {
            $user->fill($this->request->input());
            $v = ['password' => 'required|min:6', 'email' => ['required', 'email', validator('unique')->model(User::class)->column('email')], 'confirm_password' => 'required|same:password'];
            $validate = $this->request->validate($this->request->input(), $v);
            if (!$validate->fails()) {
                $user->save();
                App::getInstance()->session->setFlash('success', 'test');
                return $this->response->redirect('/');
            }
            $user->errorBag->margeErrorBadges($validate->errors());
        }
        return $this->view('register', ['user' => $user]);
    }

    public function logout(Response $response): Response
    {
        auth()->logout();
        return $response->redirect('/');
    }
}