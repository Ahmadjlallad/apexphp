<?php

use Apex\src\App;
use Apex\src\Model\User;
use Apex\src\Model\Model;
use Apex\src\Model\Validation\Validator;
use Rakit\Validation\ErrorBag;
use Rakit\Validation\Rule;
use Rakit\Validation\RuleNotFoundException;

function NOT_IMPLEMENTED(): void
{
    dd('IMPLEMENTED');
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
    return App::getInstance()->session->getFlash($name) ?? new ErrorBag;
}

function params(?string $name = null): array|null|string
{
    $params = App::getInstance()->session->getFlash('params');
    if (empty($params)) {
        return !empty($name) ? null: [];
    }
    if ($name) {
        return $params[$name];
    }
    return $params;
}
function auth(): ?User
{
    return App::getInstance()->user;
}
function vLog(mixed ...$values) {
    return \Symfony\Component\VarDumper\VarDumper::dump(func_get_args());
}
