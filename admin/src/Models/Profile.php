<?php

namespace App\Models;

use App\Core\Model;

class Profile extends Model
{
    protected string $table = 'profile';
    protected string $primaryKey = 'id';

    /**
     * Get the profile (single row, always ID 1).
     */
    public function get(): ?array
    {
        return $this->find(1);
    }

    /**
     * Update the profile.
     */
    public function updateProfile(array $data): int
    {
        return $this->update(1, $data);
    }
}