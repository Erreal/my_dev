<?php
/**
 * Authentication management.
 */

namespace App\Core;

use App\Config\Database;

class Auth
{
    /**
     * Attempt to log in with username and password.
     */
    public static function attempt(string $username, string $password): bool
    {
        $db = Database::getInstance();
        $user = $db->fetchOne(
            'SELECT id, username, password_hash FROM admin_users WHERE username = ?',
            [$username]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        Session::start();
        Session::regenerate();
        Session::set('admin_id', $user['id']);
        Session::set('admin_username', $user['username']);
        Session::set('admin_logged_in', true);

        return true;
    }

    /**
     * Check if the user is authenticated.
     */
    public static function check(): bool
    {
        Session::start();
        return Session::get('admin_logged_in') === true;
    }

    /**
     * Get the authenticated user's ID.
     */
    public static function id(): ?int
    {
        Session::start();
        return Session::get('admin_id');
    }

    /**
     * Get the authenticated user's username.
     */
    public static function user(): ?string
    {
        Session::start();
        return Session::get('admin_username');
    }

    /**
     * Log out the current user.
     */
    public static function logout(): void
    {
        Session::start();
        Session::destroy();
    }

    /**
     * Require authentication. Redirect to login if not authenticated.
     */
    public static function requireAuth(): void
    {
        if (!self::check()) {
            Session::setFlash('error', 'Пожалуйста, войдите в систему.');
            header('Location: /admin/login');
            exit;
        }
    }
}