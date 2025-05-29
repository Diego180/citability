<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Query {
    private $conn;
    private $table = 'queries';

    public $id;
    public $text;
    public $category;
    public $brand_id;
    public $created_at;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Find queries by brand ID
     * @param int $brand_id
     * @return array An array of query objects
     */
    public function findByBrandId(int $brand_id): array
    {
        $sql = 'SELECT id, text, category, brand_id, created_at FROM ' . $this->table . ' WHERE brand_id = :brand_id ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create a new query for a brand
     * @param string $text
     * @param string|null $category
     * @param int $brand_id
     * @return bool
     */
    public function create(string $text, ?string $category, int $brand_id): bool
    {
        $sql = 'INSERT INTO ' . $this->table . ' (text, category, brand_id) VALUES (:text, :category, :brand_id)';
        $stmt = $this->conn->prepare($sql);

        // Sanitize data
        $text = htmlspecialchars(strip_tags($text));
        $category = $category ? htmlspecialchars(strip_tags($category)) : null;

        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->text = $text;
            $this->category = $category;
            $this->brand_id = $brand_id;
            return true;
        }
        return false;
    }

    /**
     * Find a query by its ID and Brand ID (for verification)
     * @param int $id
     * @param int $brand_id
     * @return object|false
     */
    public function findByIdAndBrandId(int $id, int $brand_id): object|false
    {
        $sql = 'SELECT id, text, category, brand_id, created_at FROM ' . $this->table . ' WHERE id = :id AND brand_id = :brand_id LIMIT 1';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Potential future methods: delete, update, etc.
}

