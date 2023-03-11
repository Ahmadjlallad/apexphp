<?php

use Apex\src\App;
use Apex\src\Model\User;
use Apex\src\Model\Validation\Validator;
use Apex\src\Session\Session;
use Rakit\Validation\ErrorBag;
use Rakit\Validation\Rule;
use Rakit\Validation\RuleNotFoundException;
use Symfony\Component\VarDumper\VarDumper;

function NOT_IMPLEMENTED(): void
{
    dd('IMPLEMENTED');
}

function app(): App
{
    return App::getInstance();
}

/**
 * @throws RuleNotFoundException
 */
function validator(string $ruleName): Rule
{
    return (new Validator())($ruleName);
}

function errors(string $name = 'errors'): ErrorBag
{
    return session()->getFlash($name) ?? new ErrorBag;
}

function params(?string $name = null): array|null|string
{
    $params = session()->getFlash('params');
    if (empty($params)) {
        return !empty($name) ? null : [];
    }
    if ($name) {
        return $params[$name];
    }
    return $params;
}

function auth(): ?User
{
    return app()->user;
}

function vLog(mixed ...$values): array
{
    $vars = [];
    foreach ($values as $value) {
        $vars[] = VarDumper::dump($value);
    }
    return $vars;
}

/**
 * @param string $message
 * @return void
 */
function infoLog(string $message): void
{
    VarDumper::dump(sprintf("INFO AT [%s] - %s", date('y-m-d H:i:s'), $message));
}

function session(): Session
{
    return app()->session;
}
