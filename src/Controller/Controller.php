<?php

namespace Apex\src\Controller;

use Apex\src\App;

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

    protected function view(string $viewName, array $params = []): bool|string
    {
        return App::getInstance()->view->view($viewName, $params);
    }
}