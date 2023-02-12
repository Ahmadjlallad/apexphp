<?php

namespace Apex\src\Controller;

use Apex\src\App;
use Apex\src\Request;
use Apex\src\Response;

class Controller extends AbstractController
{
    public Response $response;

    public function __construct(public Request $request, Response $response = null)
    {
        if (empty($response)) {
            $response = App::getInstance()->response;
        }
        $this->response = $response;
    }

    public function callAction(string $method, array $params = []): mixed
    {
        return $this->{$method}(...array_values($params));
    }

    public function setLayout(string $layoutName): void
    {
        App::getInstance()->views->setLayout($layoutName);
    }

    public function __get(string $name)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line']);
        return null;
    }

    protected function view(string $viewName, array $params = []): Response
    {
        return App::getInstance()->views->view($viewName, $params);
    }

    protected function asJson(array $json): Response
    {
        return Response::makeResponse($json);
    }
}