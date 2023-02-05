<?php

namespace Apex\src\Exceptions;

class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = 'You don\'t have permission ti access this page';
}