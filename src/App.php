<?php
declare(strict_types=1);

namespace Apex\src;

use Apex\src\Database\Database;
use Apex\src\Router\Router;
use Apex\src\Session\Session;
use Apex\src\View\View;
use Exception;

class App
{
    public static string $ROOT_DIR;
    public static string $VIEWS_DIR;
    static public array $config = [];
    private static App $instance;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public View $view;
    public Database $db;

    public function __construct($config = [])
    {
        self::$ROOT_DIR = $config['ROOT_DIR'] ?? dirname(__DIR__);
        self::$VIEWS_DIR = $config['VIEWS_DIR'] ?? self::$ROOT_DIR . '/resources/views';
        static::$instance = $this;
        $this->session = new Session();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        $this->view = new View();
        $this->db = new Database($config['db'] ?? static::$config['db']);
        static::$config = $config;
    }

    public static function getInstance(): App
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function run(): void
    {
        try {
            $res = $this->router->resolve();
            $res->processResponse();
        } catch (Exception $exception) {
            dd($exception);
        }
    }
}