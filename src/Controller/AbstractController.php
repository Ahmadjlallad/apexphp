<?php

namespace Apex\src\Controller;


use Apex\src\Http\Http;

abstract class AbstractController extends Http
{

    abstract protected function view(string $viewName, array $params);

    abstract protected function callAction(string $method, array $params = []): mixed;
}