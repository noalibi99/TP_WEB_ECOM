<?php
session_start();

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    if (in_array($productId, $_SESSION['wishlist'])) {
        // Remove product from wishlist
        $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function($id) use ($productId) {
            return $id != $productId;
        });
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);

        echo json_encode([
            'success' => true,
            'status' => 'removed',
            'message' => 'Product removed from wishlist.',
            'wishlist' => $_SESSION['wishlist']
        ]);
    } else {
        // Add product to wishlist
        $_SESSION['wishlist'][] = $productId;

        echo json_encode([
            'success' => true,
            'status' => 'added',
            'message' => 'Product added to wishlist.',
            'wishlist' => $_SESSION['wishlist']
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No product ID provided.'
    ]);
}
?>
