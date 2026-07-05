<?php
/**
 * Base controller class.
 */

namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected View $view;
    protected ?string $layout = 'default';

    public function __construct()
    {
        $this->request = new Request();
        $this->view = new View();

        // Set global data
        $this->view->setGlobal('app_name', APP_NAME);
        $this->view->setGlobal('current_path', $this->request->getPath());
        $this->view->setGlobal('flash', Session::getFlash());
        $this->view->setGlobal('auth_user', Auth::check() ? Auth::user() : null);
    }

    /**
     * Redirect to a URL.
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back to the previous page.
     */
    protected function redirectBack(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/admin/dashboard';
        $this->redirect($referer);
    }

    /**
     * Render a view.
     */
    protected function render(string $view, array $data = []): string
    {
        return $this->view->render($view, $data, $this->layout);
    }

    /**
     * Display a view directly.
     */
    protected function display(string $view, array $data = []): void
    {
        $this->view->display($view, $data, $this->layout);
    }

    /**
     * Return JSON response.
     */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Generate a CSRF token.
     */
    protected function csrfToken(): string
    {
        if (!Session::has('_csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('_csrf_token', $token);
        }
        return Session::get('_csrf_token');
    }

    /**
     * Validate CSRF token.
     */
    protected function validateCsrf(): bool
    {
        $token = $this->request->post('_csrf_token');
        $stored = Session::get('_csrf_token');

        if (!$token || !$stored || !hash_equals($stored, $token)) {
            Session::setFlash('error', 'Недействительный CSRF-токен.');
            return false;
        }

        return true;
    }
}