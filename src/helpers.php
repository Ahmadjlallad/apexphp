<?php

use JetBrains\PhpStorm\NoReturn;
use Rakit\Validation\RuleNotFoundException;

#[NoReturn] function NOT_IMPLEMENTED(): void
{
    dd('IMPLEMENTED');
}

/**
 * @throws RuleNotFoundException
 */
function validator(string $ruleName): \Rakit\Validation\Rule
{
    return (new \Apex\src\Model\Validation\Validator())($ruleName);
}