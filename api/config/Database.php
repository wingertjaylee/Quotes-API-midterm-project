<?php
class Database {
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $host = getenv('DB_HOST');
            $db_name = getenv('DB_NAME');
            $username = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');
            $port = getenv('DB_PORT') ?: '5432';

            $dsn = "pgsql:host={$host};port={$port};dbname={$db_name};sslmode=require";

            $this->conn = new PDO($dsn, $username, $password, [
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

