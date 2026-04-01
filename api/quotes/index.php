<?php
// Quotes API endpoint for handling CRUD operations related to quotes
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quoteObj = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];
// Handle the request based on the HTTP method
if ($method === 'GET') {
    $id = isset($_GET['id']) ? (INT)$_GET['id'] : null;
    $author_id = isset($_GET['author_id']) ? (INT)$_GET['author_id'] : null;
    $category_id = isset($_GET['category_id']) ? (INT)$_GET['category_id'] : null;
    $random = (isset($_GET['random']) && $_GET['random'] === 'true');

    $result = $quoteObj->read($id, $author_id, $category_id, $random);

    $quotes_arr = [];
//  Fetch quotes and build the response array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $quotes_arr[] = [
            'id' => $row['id'],
            'quote' => $row['quote'],
            'author' => $row['author'],
            'category' => $row['category']
        ];
    }

    if (count($quotes_arr) > 0) {
        echo json_encode($quotes_arr);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
// Handle POST request to create a new quote
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

if (
    !isset($data->quote) || trim($data->quote) === '' ||
    !isset($data->author_id) ||
    !isset($data->category_id)
) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
}

    if (!$quoteObj->authorExists($data->author_id)) {
        echo json_encode(['message' => 'author_id Not Found']);
        exit;
    }

    if (!$quoteObj->categoryExists($data->category_id)) {
        echo json_encode(['message' => 'category_id Not Found']);
        exit;
    }

    $quoteObj->quote = $data->quote;
    $quoteObj->author_id = $data->author_id;
    $quoteObj->category_id = $data->category_id;
//  Attempt to create the quote and return the appropriate response
    if ($quoteObj->create()) {
        echo json_encode([
            'id' => $quoteObj->id,
            'quote' => $quoteObj->quote,
            'author_id' => $quoteObj->author_id,
            'category_id' => $quoteObj->category_id
        ]);
    } else {
        echo json_encode(['message' => 'Quote Not Created']);
    }
}
// Handle PUT request to update an existing quote
if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset($data->id) ||
        !isset($data->quote) || trim($data->quote) === '' ||
        !isset($data->author_id) ||
        !isset($data->category_id)
        )
    {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
}

    if (!$quoteObj->authorExists($data->author_id)) {
        echo json_encode(['message' => 'author_id Not Found']);
        exit;
    }

    if (!$quoteObj->categoryExists($data->category_id)) {
        echo json_encode(['message' => 'category_id Not Found']);
        exit;
    }

    $quoteObj->id = $data->id;
    $quoteObj->quote = $data->quote;
    $quoteObj->author_id = $data->author_id;
    $quoteObj->category_id = $data->category_id;

    if ($quoteObj->update()) {
        echo json_encode([
            'id' => $quoteObj->id,
            'quote' => $quoteObj->quote,
            'author_id' => $quoteObj->author_id,
            'category_id' => $quoteObj->category_id
        ]);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
// Handle DELETE request to delete an existing quote
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id) || empty($data->id)) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    $quoteObj->id = $data->id;

    if ($quoteObj->delete()) {
        echo json_encode([
            'id' => $quoteObj->id
        ]);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
?>