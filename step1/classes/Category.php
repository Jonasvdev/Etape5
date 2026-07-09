<?php
require_once __DIR__ . '/../config/database.php';

class Category
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM categories');
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $name, ?int $parentId): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, parent_id) VALUES (:name, :parent_id)'
        );
        return $stmt->execute([
            'name' => $name,
            'parent_id' => $parentId,
        ]);
    }

    public function update(int $id, string $name, ?int $parentId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE categories SET name = :name, parent_id = :parent_id WHERE id = :id'
        );
        return $stmt->execute([
            'name' => $name,
            'parent_id' => $parentId,
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}




