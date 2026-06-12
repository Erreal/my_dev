<?php
/**
 * HTTP Request wrapper.
 */

namespace App\Core;

class Request
{
    private array $queryParams;
    private array $bodyParams;
    private array $files;
    private array $server;

    public function __construct()
    {
        $this->queryParams = $_GET;
        $this->bodyParams = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    /**
     * Get the HTTP method.
     */
    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get the URI path.
     */
    public function getPath(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Get a query parameter.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Get a POST/body parameter.
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->bodyParams[$key] ?? $default;
    }

    /**
     * Get all POST data.
     */
    public function all(): array
    {
        return $this->bodyParams;
    }

    /**
     * Check if a POST parameter exists.
     */
    public function has(string $key): bool
    {
        return isset($this->bodyParams[$key]);
    }

    /**
     * Get an uploaded file.
     */
    public function file(string $key): ?array
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] !== UPLOAD_ERR_NO_FILE
            ? $this->files[$key]
            : null;
    }

    /**
     * Check if the request is an AJAX request.
     */
    public function isAjax(): bool
    {
        return (isset($this->server['HTTP_X_REQUESTED_WITH'])
            && strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    /**
     * Get the base URL.
     */
    public function getBaseUrl(): string
    {
        $protocol = isset($this->server['HTTPS']) && $this->server['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
}