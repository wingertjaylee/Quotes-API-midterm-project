<?php
// Category model class for handling database operations related to categories
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;
// Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }
// Method to read categories from the database, with optional filtering by ID
    public function read() {
        $query = 'SELECT id, category
            FROM ' . $this->table . '
            ORDER BY id';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
// Method to read a single category by ID
    public function read_single() {
        $query = 'SELECT id, category
            FROM ' . $this->table . '
            WHERE id = :id
            LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }
// Method to create a new category
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
        $stmt = $this->conn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
// Method to update an existing category
    public function update() {
        $query = 'UPDATE ' . $this->table . '
        SET category = :category
        WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }
//  Method to delete an existing category
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return $stmt->rowCount() > 0;
            }
        } catch (PDOException $e) {
            return false;
        }

        return false;
    }
}
?>