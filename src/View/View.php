<?php

namespace Apex\src\View;

use Apex\src\App;

// todo change how view class works
class View
{
    private string $layout = 'main';

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

    public function view(string $viewName, array $params = []): bool|string
    {
        $view = $this->getViewContent($viewName, $params);
        $layoutContent = $this->getCurrentLayout();

        return str_replace('@content()', $view, $layoutContent);
    }

    private function getViewContent(string $view, array $params = []): bool|string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
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