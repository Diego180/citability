<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Brand {
    private $conn;
    private $table = 'brands';

    public $id;
    public $name;
    public $domain;
    public $user_id;
    public $created_at;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Find brand by user ID (assuming one brand per user for prototype)
     * @param int $user_id
     * @return object|false
     */
    public function findByUserId(int $user_id): object|false
    {
        $query = 'SELECT id, name, domain, user_id, created_at FROM ' . $this->table . ' WHERE user_id = :user_id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Create a new brand for a user
     * @param string $name
     * @param string $domain
     * @param int $user_id
     * @return bool
     */
    public function create(string $name, string $domain, int $user_id): bool
    {
        // Check if user already has a brand (enforce one brand per user)
        if ($this->findByUserId($user_id)) {
            return false; // User already has a brand
        }

        $query = 'INSERT INTO ' . $this->table . ' (name, domain, user_id) VALUES (:name, :domain, :user_id)';
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $name = htmlspecialchars(strip_tags($name));
        $domain = htmlspecialchars(strip_tags($domain));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':domain', $domain);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->name = $name;
            $this->domain = $domain;
            $this->user_id = $user_id;
            return true;
        }
        return false;
    }

    /**
     * Update an existing brand
     * @param int $id
     * @param string $name
     * @param string $domain
     * @param int $user_id (for verification)
     * @return bool
     */
    public function update(int $id, string $name, string $domain, int $user_id): bool
    {
        $query = 'UPDATE ' . $this->table . ' SET name = :name, domain = :domain WHERE id = :id AND user_id = :user_id';
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $name = htmlspecialchars(strip_tags($name));
        $domain = htmlspecialchars(strip_tags($domain));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':domain', $domain);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

     /**
     * Find brand by ID and User ID (for security checks)
     * @param int $id
     * @param int $user_id
     * @return object|false
     */
    public function findByIdAndUserId(int $id, int $user_id): object|false
    {
        $query = 'SELECT id, name, domain, user_id, created_at FROM ' . $this->table . ' WHERE id = :id AND user_id = :user_id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}

