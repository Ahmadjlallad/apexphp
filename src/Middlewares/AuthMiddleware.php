<?php

namespace Apex\src\Middlewares;

use Apex\src\App;
use Apex\src\Exceptions\ForbiddenException;
use Apex\src\Request;
use Closure;

class AuthMiddleware implements MiddlewareInterface
{


    public function __construct(public bool $haveToBeAuthenticated = true)
    {

    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ((auth() && $this->haveToBeAuthenticated) || (!auth() && !$this->haveToBeAuthenticated)) {
            return $next($request);
        }
        if (!$this->haveToBeAuthenticated) {
            return app()->response->back();
        }
        return app()->response->redirect('/', 401);
    }
}