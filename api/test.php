<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/quotes-api/api/config/Database.php';

$database = new Database();
$db = $database->connect();

if ($db) {
    echo 'Database connection successful!';
} else {
    echo 'Database connection failed!';
}
?>