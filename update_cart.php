<?php
session_start();
header('Content-Type: application/json');

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['cart']) || !is_array($input['cart'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update session cart
foreach ($input['cart'] as $id => $qty) {
    $id = trim($id);
    $qty = (int)$qty;
    if ($qty > 0) {
        $_SESSION['cart'][$id] = $qty;
    } else {
        // Remove product if qty 0 or less
        unset($_SESSION['cart'][$id]);
    }
}

echo json_encode([
    'success' => true,
    'cart' => $_SESSION['cart']
]);
