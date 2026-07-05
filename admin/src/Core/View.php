<?php
/**
 * View renderer.
 */

namespace App\Core;

class View
{
    private string $viewsPath;
    private array $globalData = [];

    public function __construct()
    {
        $this->viewsPath = VIEWS_PATH;
    }

    /**
     * Set global data available to all views.
     */
    public function setGlobal(string $key, mixed $value): void
    {
        $this->globalData[$key] = $value;
    }

    /**
     * Render a view with layout.
     */
    public function render(string $view, array $data = [], string $layout = 'default'): string
    {
        $data = array_merge($this->globalData, $data);
        $content = $this->renderPartial($view, $data);

        if ($layout) {
            return $this->renderPartial("layouts/{$layout}", array_merge($data, ['content' => $content]));
        }

        return $content;
    }

    /**
     * Render a view without layout.
     */
    public function renderPartial(string $view, array $data = []): string
    {
        $viewPath = $this->viewsPath . '/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $viewPath;
        return ob_get_clean();
    }

    /**
     * Escape HTML special characters.
     */
    public static function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Render and output directly.
     */
    public function display(string $view, array $data = [], string $layout = 'default'): void
    {
        echo $this->render($view, $data, $layout);
    }
}