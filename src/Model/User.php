<?php

namespace Apex\src\Model;

use Apex\src\App;

abstract class User extends Model
{
    protected string $searchableBy = 'email';
    protected array $hidden = [
        'password',
    ];

    public function login(): bool
    {
        $user = $this->makeVisible(['password'])->firstWhere([$this->searchableBy => $this->email]);
        $error = session()->getFlash('errors');
        if (empty($error)) {
            $error = new ErrorBag();
        }
        if (!$user) {
            $error->addError('email', 'User dose not exist with this ' . $this->{$this->searchableBy});
            session()->setFlash('errors', $error);
            session()->setFlash('params', [...(session()->getFlash('params') ?? []), $this->searchableBy => $this->{$this->searchableBy}]);
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $error->addError('password', 'Password is Incorrect');
            session()->setFlash('params', [...(session()->getFlash('params') ?? []), $this->searchableBy => $this->{$this->searchableBy}]);
            session()->setFlash('errors', $error);
            return false;
        }
        App::getInstance()->login($user);
        return true;
    }

    public function logout(): void
    {
        App::getInstance()->logout();
    }
}