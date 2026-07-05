<?php
/**
 * Base model class.
 */

namespace App\Core;

use App\Config\Database;

abstract class Model
{
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a record by its primary key.
     */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Get all records, ordered by a column.
     */
    public function all(string $orderBy = 'sort_order', string $direction = 'ASC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}"
        );
    }

    /**
     * Create a new record.
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        return $this->db->insert(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );
    }

    /**
     * Update a record.
     */
    public function update(int $id, array $data): int
    {
        $sets = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;

        return $this->db->execute(
            "UPDATE {$this->table} SET {$sets} WHERE {$this->primaryKey} = ?",
            $values
        );
    }

    /**
     * Delete a record.
     */
    public function delete(int $id): int
    {
        return $this->db->execute(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Count all records.
     */
    public function count(): int
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM {$this->table}");
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get records with pagination.
     */
    public function paginate(int $page = 1, int $perPage = 20, string $orderBy = 'sort_order', string $direction = 'ASC'): array
    {
        $offset = ($page - 1) * $perPage;
        $total = $this->count();
        $items = $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction} LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return [
            'items'      => $items,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'lastPage'   => (int) ceil($total / $perPage),
        ];
    }
}