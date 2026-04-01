<?php
// Quote model class for handling database operations related to quotes
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
// Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }
//  Method to read quotes from the database, with optional filtering by ID, author_id, category_id, and random selection
    public function read($id = null, $author_id = null, $category_id = null, $random = false ) {
        $query = 'SELECT       
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
            FROM ' . $this->table . '
            JOIN authors ON quotes.author_id = authors.id
            JOIN categories ON quotes.category_id = categories.id';
// Build dynamic WHERE clause based on provided parameters
        $conditions = array();

        if ($id !== null) {
            $conditions[] = 'quotes.id = :id';
        }

        if ($author_id !== null) {
            $conditions[] = 'quotes.author_id = :author_id';
        }

        if ($category_id !== null) {
            $conditions[] = 'quotes.category_id = :category_id';
        }

        if (count($conditions) > 0) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        if ($random) {
        $query .= ' ORDER BY RANDOM() LIMIT 1';
        } else {
        $query .= ' ORDER BY quotes.id';
        }

        $stmt = $this->conn->prepare($query);

        if ($id !== null) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }

        if ($author_id !== null) {
            $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        }

        if ($category_id !== null) {
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }
// Method to create a new quote
    public function create() {
    $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id)
        VALUES (:quote, :author_id, :category_id)
        RETURNING id';

    $stmt = $this->conn->prepare($query);

    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));

    $stmt->bindParam(':quote', $this->quote);
    $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        return true;
    }

    return false;
}
// Helper methods to check if an author_id or category_id exists before creating or updating a quote
public function authorExists($author_id) {
    $query = 'SELECT id FROM authors WHERE id = :id LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $author_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}
//  Method to check if a category_id exists before creating or updating a quote
    public function categoryExists($category_id) {
    $query = 'SELECT id FROM categories WHERE id = :id LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}
// Method to update an existing quote
    public function update() {
        $query = 'UPDATE ' . $this->table . '
            SET quote = :quote,
            author_id = :author_id,
            category_id = :category_id
            WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }
// Method to delete an existing quote
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }
}
?>