<?php
// Categories API Endpoint
// Headers handle CORS and specify content type and allowed methods
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Category.php';

$database = new Database();
$db = $database->connect();

$categoryObj = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];
// Handle the request based on the HTTP method
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $categoryObj->id = $_GET['id'];
        $result = $categoryObj->read_single();
    } else {
        $result = $categoryObj->read();
    }

    $num = $result->rowCount();

    if ($num > 0) {
        $categories_arr = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categories_arr[] = [
                'id' => $row['id'],
                'category' => $row['category']
            ];
        }

        echo json_encode($categories_arr);
    } else {
        echo json_encode(['message' => 'category_id Not Found']);
    }
}
//  Handle POST request to create a new category
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->category) || empty(trim($data->category))) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $categoryObj->category = $data->category;

    if ($categoryObj->create()) {
        echo json_encode([
            'id' => $categoryObj->id,
            'category' => $categoryObj->category
        ]);
    } else {
        echo json_encode(['message' => 'category Not Created']);
    }
}
// Handle PUT request to update an existing category
if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id) || !isset($data->category) || empty(trim($data->category))) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $categoryObj->id = $data->id;
    $categoryObj->category = $data->category;

    if ($categoryObj->update()) {
        echo json_encode([
            'id' => $categoryObj->id,
            'category' => $categoryObj->category
        ]);
    } else {
        echo json_encode(['message' => 'category_id Not Found']);
    }
}
// Handle DELETE request to delete an existing category
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id)) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $categoryObj->id = $data->id;

    if ($categoryObj->delete()) {
        echo json_encode(['id' => $categoryObj->id]);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
?>