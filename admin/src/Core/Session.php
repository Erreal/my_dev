<?php
/**
 * Session management.
 */

namespace App\Core;

class Session
{
    /**
     * Start the session if not already started.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path'     => '/admin',
                'secure'   => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    /**
     * Set a session value.
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session key exists.
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session key.
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session.
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Set a flash message (available for one request).
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type] = $message;
    }

    /**
     * Get and clear flash messages.
     */
    public static function getFlash(): array
    {
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }

    /**
     * Regenerate session ID (call after login).
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }
}