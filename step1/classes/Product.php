<?php
require_once __DIR__ . '/../config/database.php';

class Product
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM products');
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $name, string $description, float $price, string $image): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products (name, description, price, image) VALUES (:name, :description, :price, :image)'
        );
        return $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image,
        ]);
    }

    public function update(int $id, string $name, string $description, float $price, ?string $image): bool
    {
        // $productData['category_id'] = $categoryId;

        if ($image !== null) {
            $stmt = $this->db->prepare(
                'UPDATE products SET name = :name, description = :description, price = :price, image = :image WHERE id = :id'
            );
            return $stmt->execute([
                'name' => $name, 'description' => $description,
                'price' => $price, 'image' => $image, 'id' => $id,
                //'category_id' => $categoryId, 'id' => $id,
            ]);
        }

        $stmt = $this->db->prepare(
            'UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id'
        );
        return $stmt->execute([
            'name' => $name, 'description' => $description,
            'price' => $price, 'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

public function getPaginated(int $limit, int $offset): array
{
    $stmt = $this->db->prepare('SELECT * FROM products LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function count(): int
{
    $stmt = $this->db->query('SELECT COUNT(*) FROM products');
    return (int)$stmt->fetchColumn();
}
 
// Partie de recherche de produits par catégorie


public function search(string $name, ?int $categoryId, ?float $minPrice, ?float $maxPrice, string $sort): array
{
    $sql = 'SELECT * FROM products WHERE name LIKE :name';
    $params = ['name' => '%' . $name . '%'];

    if ($categoryId !== null) {
        $sql .= ' AND category_id = :category_id';
        $params['category_id'] = $categoryId;
    }

    if ($minPrice !== null) {
        $sql .= ' AND price >= :min_price';
        $params['min_price'] = $minPrice;
    }

    if ($maxPrice !== null) {
        $sql .= ' AND price <= :max_price';
        $params['max_price'] = $maxPrice;
    }

    $allowedSorts = [
        'name_asc' => 'name ASC',
        'name_desc' => 'name DESC',
        'price_asc' => 'price ASC',
        'price_desc' => 'price DESC',
    ];

    $sql .= ' ORDER BY ' . ($allowedSorts[$sort] ?? $allowedSorts['name_asc']);

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}




}





