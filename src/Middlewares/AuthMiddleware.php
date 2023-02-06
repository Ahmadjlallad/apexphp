<?php

namespace Apex\src\Middlewares;

use Apex\src\App;
use Apex\src\Exceptions\ForbiddenException;
use Apex\src\Request;
use Closure;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()) {
            return App::getInstance()->response->redirect('/', 401);
        }
        return $next($request);
    }
}