<?php

namespace Apex\src\Router;

use Apex\src\App;
use Apex\src\Controller\Controller;
use Apex\src\Request;
use Apex\src\Response;
use Exception;
use ReflectionClass;
use ReflectionException;

class RoutesHandler
{
    /**
     * [method => [path => Route]]
     * @var array
     */
    public static array $routes = [];

    public function __construct(private readonly Request $request)
    {
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function resolve(): Response|string
    {
        $path = $this->request->getPath();
        $method = strtoupper($this->request->getHttpMethod());
        /**
         * @var ?Router $route
         */
        $route = static::$routes[$method][$path] ?? null;
        if (!$route) {
            return App::getInstance()->view->view('404');
        }
        $routeAction = $route->action;
        if (is_array($routeAction)) {
            /** @var Controller $controller */
            $controller = new $routeAction[0](App::getInstance()->request);

            foreach ($route->middlewares as $middleware) {
                $result = $middleware->handle($controller->request, function (Request $request) use ($controller) {
                    $controller->request = $request;
                    return $controller;
                });
                if ($result instanceof Response) {
                    return $result;
                }
            }

            try {
                /** @var Controller $controller */
                $ref = new \ReflectionClass($routeAction[0]);
                $refParams = $ref->getMethod($routeAction[1])->getParameters();
                $actionParameters = [];
                foreach ($refParams as $parameter) {
                    if ($parameter->getType()->isBuiltin()) {
                        throw new ReflectionException('Resolving Builtin type in DI');
                    }
                    if ($parameter->getType()->getName() === Request::class) {
                        $actionParameters[] = $controller->request;
                        continue;
                    }
                    $checkParameters = new \ReflectionClass($parameter->getType()->getName());
                    if ($this->resolveDI($checkParameters, $controller->request)) {
                        $actionParameters[] = new ($parameter->getType()->getName());
                    }
                }
                return $controller->callAction($routeAction[1], $actionParameters);
            } catch (ReflectionException $exception) {
                dd($exception);
            }
        }
        if (is_string($routeAction)) {
            return App::getInstance()->view->viewContent($routeAction);
        }
        return call_user_func($routeAction);
    }

    /**
     * @throws ReflectionException
     */
    public function resolveDI(ReflectionClass $replicationClass, Request $request): true
    {
        $constructor = $replicationClass->getConstructor();
        if (!$constructor) {
            return true;
        }
        if (!empty($constructor->getParameters())) {
            throw new ReflectionException('Resolving unknown parameters in class '.$replicationClass->getName(). ' constructor '. implode(", ", $constructor->getParameters()));
        }
        return true;
//        foreach ($constructor->getParameters() as $attribute) {
//            // @TODO create DI array in the app so we can resolve the known parameters
//        }
    }
}