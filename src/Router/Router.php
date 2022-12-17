<?php
declare(strict_types=1);

namespace Apex\src\Router;

use Apex\src\App;
use Apex\src\Request;
use Apex\src\Response;
use Closure;

class Router implements RouterInterface
{
    public Request $request;
    protected array $routes = [];
    protected Response $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function post(string $path, Closure|callable|array|string|null $action): void
    {
        $this->routes['post'][$path] = $action;
    }

    public function get(string $path, string|Closure|null|callable|array $action): void
    {
        $this->routes['get'][$path] = $action;
    }

    public function resolve()
    {

        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $action = $this->routes[$method][$path] ?? null;

        if (is_array($action)) {
            $controller = new $action[0];
//            dd($action, $controller, );
            return $controller->{$action[1]}();
        }
        if (!$action) {
            return $this->constructView('404');
        }
        if (is_string($action)) {
            return $this->constructView($action);
        }
        return call_user_func($action);
    }

    private function constructView(string $viewName): bool|string
    {
        $view = $this->getViewOnly($viewName);
        $layoutContent = $this->getCurrentLayout();

        return str_replace('@content()', $view, $layoutContent);
    }

    protected function getViewOnly(string $view): bool|string
    {
        ob_start();
        include_once App::$VIEWS_DIR . '/' . $view . '.php';
        $viewsContent = ob_get_clean();
        if (!$viewsContent) {
            // TODO handle not found
            $this->response->setStatus(422);
            dd('Views NOT FOUND ' . 'line: ' . __LINE__ . ' class: ' . self::class . ' Views name: ' . $view);
        }
        return $viewsContent;
    }

    private function getCurrentLayout(): bool|string
    {
        ob_start();
        include_once App::$VIEWS_DIR . '/layouts/main.php';
        $layout = ob_get_clean();
        if (!$layout) {
            // TODO handle not found
            $this->response->setStatus(422);
            dd('LAYOUT NOT FOUND', __LINE__, self::class);
        }
        return $layout;
    }

    private function constructContent(string $content): bool|string
    {
        $layoutContent = $this->getCurrentLayout();

        return str_replace('@content()', $content, $layoutContent);
    }

}