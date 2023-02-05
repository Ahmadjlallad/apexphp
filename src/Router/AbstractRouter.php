<?php

namespace Apex\src\Router;

use Apex\src\Http\Http;
use Apex\src\Middlewares\MiddlewareInterface;

abstract class AbstractRouter extends Http
{
    static abstract public function get(string $path, array|string|callable|null $action): static;

    static abstract public function post(string $path, array|string|callable|null $action): static;
}