<?php

namespace App\Models;

use App\Core\Model;

class AdminUser extends Model
{
    protected string $table = 'admin_users';

    /**
     * Find a user by username.
     */
    public function findByUsername(string $username): ?array
    {
        return $this->db->fetchOne(
            'SELECT * FROM admin_users WHERE username = ?',
            [$username]
        );
    }

    /**
     * Update the password for a user.
     */
    public function updatePassword(int $id, string $newPassword): int
    {
        return $this->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT),
        ]);
    }
}