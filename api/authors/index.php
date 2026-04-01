<?php
//authors API Endpoint
// Headers handle CORS and specify content type and allowed methods
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Author.php';

$database = new Database();
$db = $database->connect();

$authorObj = new Author($db);
// Get the HTTP method of the request
$method = $_SERVER['REQUEST_METHOD'];
// Handle the request based on the HTTP method
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $result = $authorObj->read($_GET['id']);
    } else {
        $result = $authorObj->read();
    }

    $num = $result->rowCount();

    if ($num > 0) {
        $authors_arr = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $authors_arr[] = [
                'id' => $row['id'],
                'author' => $row['author']
            ];
        }

        echo json_encode($authors_arr);
    } else {
        echo json_encode(['message' => 'author_id Not Found']);
    }
}
// Handle POST request to create a new author
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->author) || empty(trim($data->author))) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $authorObj->author = $data->author;

    if ($authorObj->create()) {
        echo json_encode([
            'id' => $authorObj->id,
            'author' => $authorObj->author
        ]);
    } else {
        echo json_encode(['message' => 'author Not Created']);
    }
}
// Handle PUT request to update an existing author
if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id) || !isset($data->author) || empty(trim($data->author))) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $authorObj->id = $data->id;
    $authorObj->author = $data->author;

    if ($authorObj->update()) {
        echo json_encode([
            'id' => $authorObj->id,
            'author' => $authorObj->author
        ]);
    } else {
        echo json_encode(['message' => 'author_id Not Found']);
    }
}
// Handle DELETE request to delete an existing author
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id)) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $authorObj->id = $data->id;

    if ($authorObj->delete()) {
        echo json_encode(['id' => $authorObj->id]);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
?>