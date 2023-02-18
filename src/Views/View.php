<?php

namespace Apex\src\Views;

use Apex\src\App;
use Apex\src\Response;

// todo change how view class works
class View
{
    public string $title = '';
    protected string $layout = 'main';

    public function viewContent(string $content): bool|string
    {
        $layoutContent = $this->getCurrentLayout();

        return str_replace('@content()', $content, $layoutContent);
    }

    private function getCurrentLayout(): bool|string
    {
        ob_start();
        include_once App::$VIEWS_DIR . "/layouts/" . "$this->layout.php";
        $this->layout = 'main';
        $layout = ob_get_clean();
        if (!$layout) {
            // TODO handle Exceptions
            dd('LAYOUT NOT FOUND', __LINE__, self::class);
        }
        return $layout;
    }

    public function view(string $viewName, array $params = []): Response
    {
        if (str_contains($viewName, '.')) {
            $viewName = str_replace('.', DIRECTORY_SEPARATOR, $viewName);
        }
        $view = $this->getViewContent($viewName, $params);
        $layoutContent = $this->getCurrentLayout();
        return Response::makeResponse(str_replace('@content()', $view, $layoutContent));
    }

    private function getViewContent(string $view, array $params = []): bool|string
    {
        extract($params);
        ob_start();
        include_once App::$VIEWS_DIR . '/' . $view . '.php';
        $viewsContent = ob_get_clean();
        if (!$viewsContent) {
            // TODO handle Exceptions
            dd('Views NOT FOUND ' . 'line: ' . __LINE__ . ' class: ' . self::class . ' Views name: ' . $view);
        }
        return $viewsContent;
    }

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }
}
