<?php

namespace Apex\src\Controller;

use Apex\src\App;
use Apex\src\Request;
use Apex\src\Response;
use Exception;
use phpDocumentor\Reflection\Types\Expression;

class Controller extends AbstractController
{
    public Response $response;

    public function __construct(public Request $request, Response $response = null)
    {
        if (empty($response)) {
            $response = app()->response;
        }
        $this->response = $response;
    }

    /**
     * @throws Exception
     */
    public function callAction(string $method, array $params = []): mixed
    {
        if (empty($returnFromController = $this->{$method}(...array_values($params)))) {
            throw new Exception('Nothing Returned from the controller');
        }
        return $returnFromController;
    }

    public function setLayout(string $layoutName): void
    {
        app()->views->setLayout($layoutName);
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
        return app()->views->view($viewName, $params);
    }

    protected function asJson(array $json): Response
    {
        return Response::makeResponse($json);
    }
}