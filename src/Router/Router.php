<?php
declare(strict_types=1);

namespace Apex\src\Router;

use Apex\src\Middlewares\MiddlewareInterface;
use Closure;

class Router extends AbstractRouter
{
    static protected array $routes = [];
    /**
     * @var MiddlewareInterface[]
     */
    public array $middlewares = [];

    /**
     * @param string $method
     * @param string $path
     * @param Closure|array|string|null $action
     */
    public function __construct(public readonly string $method, public string $path, public Closure|array|string|null $action)
    {
    }

    public static function post(string $path, Closure|callable|array|string|null $action): static
    {
        return self::makeRoute($path, $action, Methods::POST);
    }

    private static function makeRoute(string $path, string|Closure|null|callable|array $action, Methods $method): static
    {
        $router = new static($method->value, $path, $action);
        RoutesHandler::$routes[$router->method][$path] = $router;
        return $router;
    }

    public static function get(string $path, string|Closure|null|callable|array $action): static
    {
        return self::makeRoute($path, $action, Methods::GET);
    }

    public static function delete(string $path, string|Closure|null|callable|array $action): static
    {
        return self::makeRoute($path, $action, Methods::DELETE);
    }

    public static function put(string $path, string|Closure|null|callable|array $action): static
    {
        return self::makeRoute($path, $action, Methods::PUT);
    }

    /**
     * @param MiddlewareInterface[]|MiddlewareInterface $middlewares
     * @return Router
     */
    public function middleware(MiddlewareInterface|array $middlewares): static
    {
        if (is_array($middlewares)) {
            $this->middlewares = array_merge($this->middlewares, $middlewares);
        } else {
            $this->middlewares[] = $middlewares;
        }
        return $this;
    }
}