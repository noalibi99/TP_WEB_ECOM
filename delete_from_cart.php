<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing product ID']);
    exit;
}

$id = trim($input['id']);

if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

echo json_encode([
    'success' => true,
    'cart' => $_SESSION['cart']
]);
