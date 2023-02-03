<?php

namespace Apex\src\Controller;

use Apex\src\App;
use Apex\src\Response;

class Controller extends AbstractController
{

    public function callAction(string $method, array $params = []): mixed
    {
        return $this->{$method}(...array_values($params));
    }

    public function setLayout(string $layoutName): void
    {
        App::getInstance()->view->setLayout($layoutName);
    }

    protected function view(string $viewName, array $params = []): Response
    {
        return App::getInstance()->view->view($viewName, $params);
    }
    protected function asJson(array $json): Response
    {
        return Response::makeResponse($json);
    }
}