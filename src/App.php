<?php
declare(strict_types=1);

namespace Apex\src;

use Apex\src\Database\Database;
use Apex\src\Database\Processor;
use Apex\src\Model\Model;
use Apex\src\Model\User;
use Apex\src\Router\RoutesHandler;
use Apex\src\Session\Session;
use Apex\src\Views\View;
use Exception;

class App
{
    public static string $ROOT_DIR;
    public static string $VIEWS_DIR;
    static public array $config = [];
    private static App $instance;
    public RoutesHandler $routesFactory;
    public Request $request;
    public Response $response;
    public Session $session;
    public View $views;
    public Database $db;
    public ?User $user = null;
    public Container $container;
    private ?string $userClass;

    public function __construct($config = [])
    {
        static::$config = $config;
        self::$ROOT_DIR = static::$config['ROOT_DIR'] ?? dirname(__DIR__);
        self::$VIEWS_DIR = static::$config['VIEWS_DIR'] ?? self::$ROOT_DIR . '/resources/views';
        static::$instance = $this;
        $this->container = new Container();
        $this->session = new Session(self::$config['haveSession'] ?? true);
        $this->request = new Request();
        $this->response = new Response();
        $this->routesFactory = new RoutesHandler($this->request);
        $this->views = new View();
        $this->db = new Database(static::$config['db']);
        $this->userClass = $config['userClass'] ?? null;
        $this->diList();
        if ($this->session->isStarted()) {
            $this->loginFromSession();
        }
    }

    private function loginFromSession(): void
    {
        /**
         * @var User $user
         */
        $user = $this->userClass::select();
        if ($userKey = $this->session->get('user_from_session')) {
            $this->user = $user->firstWhere([$user->primaryKey => $userKey]);
        }
    }

    public static function getInstance(): App
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function login(?Model $user): void
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey;
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user_from_session', $primaryValue);
    }

    public function run(): void
    {
        try {
            $res = $this->routesFactory->resolve();
            $res->processResponse();
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function logout(): void
    {
        $this->session->remove('user_from_session');
        $this->user = null;
    }

    public function diList()
    {
        $this->container->bind(Processor::class, fn() => new Processor($this->db->pdo));
    }
}