<?php
declare(strict_types=1);

namespace Apex\src;

use Apex\src\Router\Router;
use Apex\src\View\View;
use JetBrains\PhpStorm\NoReturn;

class App
{
    public static string $ROOT_DIR;
    public static string $VIEWS_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public View $view;
    private static App $instance;

    #[NoReturn] public function __construct($ROOT_DIR = null, $VIEWS_DIR = null)
    {
        static::$instance = $this;
        self::$ROOT_DIR = $ROOT_DIR ?? dirname(__DIR__);
        self::$VIEWS_DIR = $VIEWS_DIR ?? self::$ROOT_DIR . '/resources/views';
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        $this->view = new View();
    }

    /** TODO */
    public function run(): void
    {
        $res = $this->router->resolve();
        if (!$res) {
            $this->response->setStatus(404);
            dd('VIEW NOT FOUND you maybe forget to return form controller');
        }
        echo $res;
    }

    public static function getInstance(): App
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}