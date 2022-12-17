<?php

namespace Apex\src\Http;

use Apex\src\App;
use Apex\src\Request;
use Apex\src\Response;

/** @property Response $response
 *  @property Request $request
 * */
abstract class Http
{
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

    /**
     * @return Request
     */
    private function request(): Request
    {
        return App::getInstance()->request;
    }

    /**
     * @return Response
     */
    private function response(): Response
    {
        return App::getInstance()->response;
    }

}