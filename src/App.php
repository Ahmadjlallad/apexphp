<?php
declare(strict_types=1);

namespace Apex\src;

use Apex\src\Router\Router;

class App
{
    public static string $ROOT_DIR;
    public static string $VIEWS_DIR;
    public Router $router;
    public Request $request;
    public Response $response;

    public function __construct($ROOT_DIR = null, $VIEWS_DIR = null)
    {
        self::$ROOT_DIR = $ROOT_DIR ?? dirname(__DIR__);
        self::$VIEWS_DIR = $VIEWS_DIR ?? self::$ROOT_DIR . '/resources/views';
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    /** TODO */
    public function run(): void
    {
        $res = $this->router->resolve();
        if (!$res) {
            $this->response->setStatus(404);
            dd('VIEW NOT FOUND');
        }
        echo $res;
    }
}