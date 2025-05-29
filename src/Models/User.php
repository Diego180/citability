<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password_hash;
    public $created_at;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Find user by username
     * @param string $username
     * @return object|false
     */
    public function findByUsername(string $username): object|false
    {
        $query = 'SELECT id, username, password_hash, created_at FROM ' . $this->table . ' WHERE username = :username LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ); // Fetch as object for easier access
    }

    /**
     * Create a new user
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function create(string $username, string $password): bool
    {
        // Check if username already exists
        if ($this->findByUsername($username)) {
            return false; // Username already taken
        }

        $query = 'INSERT INTO ' . $this->table . ' (username, password_hash) VALUES (:username, :password_hash)';
        $stmt = $this->conn->prepare($query);

        // Sanitize username (basic example)
        $username = htmlspecialchars(strip_tags($username));

        // Hash password using bcrypt
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $password_hash);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->username = $username;
            // Note: We don't store the plain password
            return true;
        }

        // Print error if something goes wrong (for debugging prototype)
        // printf("Error: %s.\n", $stmt->error);
        return false;
    }

    /**
     * Verify user password
     * @param string $username
     * @param string $password
     * @return object|false User object if verified, false otherwise
     */
    public function verifyPassword(string $username, string $password): object|false
    {
        $user = $this->findByUsername($username);
        if ($user && password_verify($password, $user->password_hash)) {
            // Password is correct
            // Unset password hash before returning user object
            unset($user->password_hash);
            return $user;
        }
        return false; // Invalid username or password
    }

    /**
     * Find user by ID
     * @param int $id
     * @return object|false
     */
    public function findById(int $id): object|false
    {
        $query = 'SELECT id, username, created_at FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

     /**
     * Update user password
     * @param int $id
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        $query = 'UPDATE ' . $this->table . ' SET password_hash = :password_hash WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

