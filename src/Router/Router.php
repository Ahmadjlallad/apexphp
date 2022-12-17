<?php
declare(strict_types=1);

namespace Apex\src\Router;

use Apex\src\App;
use Apex\src\Controller\Controller;
use Closure;

class Router extends AbstractRouter
{
    static protected array $routes = [];

    public static function post(string $path, Closure|callable|array|string|null $action): void
    {
        static::$routes['post'][$path] = $action;
    }

    public static function get(string $path, string|Closure|null|callable|array $action): void
    {
        static::$routes['get'][$path] = $action;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getHttpMethod();
        $action = static::$routes[$method][$path] ?? null;
        if (!$action) {
            return App::getInstance()->view->view('404');
        }
        if (is_array($action)) {
            /** @var Controller $controller */
            $controller = new $action[0];
            return $controller->callAction($action[1]);
        }
        if (is_string($action)) {
            return App::getInstance()->view->viewContent($action);
        }
        return call_user_func($action);
    }



}