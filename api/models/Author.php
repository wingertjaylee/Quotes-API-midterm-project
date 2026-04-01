<?php
// Authors API Endpoint
// Headers handle CORS and specify content type and allowed methods
class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;
// Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }
    // Method to read authors from the database, with optional filtering by ID
    public function read($id = null) {
        $query = 'SELECT id, author FROM ' . $this->table;

        if ($id !== null) {
            $query .= ' WHERE id = :id';
        }

        $query .= ' ORDER BY id';

        $stmt = $this->conn->prepare($query);

        if ($id !== null) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }
// Method to read a single author by ID
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
        $stmt = $this->conn->prepare($query);

        $this->author = htmlspecialchars(strip_tags($this->author));
        $stmt->bindParam(':author', $this->author);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
// Method to update an existing author
    public function update() {
    $query = 'UPDATE ' . $this->table . '
        SET author = :author
        WHERE id = :id';

    $stmt = $this->conn->prepare($query);

    $this->author = htmlspecialchars(strip_tags($this->author));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':author', $this->author);
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return $stmt->rowCount() > 0;
    }

    return false;
} 
// Method to delete an existing author
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


