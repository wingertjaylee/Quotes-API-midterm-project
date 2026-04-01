<?php
class Database {
    private $host = '';
private $db_name = '';
private $username = '';
private $password = '';
private $port = '5432';
    public $conn;

public function connect() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
?>

