<?php
session_start();

$id = $_POST["id"];
$qty = $_POST["qty"];
if(!isset($_SESSION["cart"][$id])){
    $_SESSION["cart"][$id] = $qty;
} else{
    $_SESSION["cart"][$id] += $qty;
}
header('Content-Type: application/json');
echo json_encode(['success' => true, 'cart' => $_SESSION["cart"]]);