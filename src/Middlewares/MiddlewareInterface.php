<?php

namespace Apex\src\Middlewares;

use Apex\src\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed;
}