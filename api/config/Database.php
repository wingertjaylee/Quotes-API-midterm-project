<?php
class Database {
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $databaseUrl = getenv('DATABASE_URL');

            if (!$databaseUrl) {
                throw new Exception('DATABASE_URL is not set');
            }

            $db = parse_url($databaseUrl);

            $host = $db['host'] ?? '';
            $port = $db['port'] ?? '5432';
            $user = $db['user'] ?? '';
            $pass = $db['pass'] ?? '';
            $name = isset($db['path']) ? ltrim($db['path'], '/') : '';

            $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode=require";

            $this->conn = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

        } catch (Throwable $e) {
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

