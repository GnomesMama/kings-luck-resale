<?php
session_start();
require_once 'includes/db_connect.php'; 


if (!isset($_SESSION['id']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    die("No user or cart data found.");
}

$user_id = $_SESSION['id'];
$cart = $_SESSION['cart']; 


$order_sql = "INSERT INTO orders (user_id, order_date) VALUES (?, NOW())";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

$item_sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $conn->prepare($item_sql);

foreach ($cart as $product_id => $quantity) {
    $stmt->bind_param("iii", $order_id, $product_id, $quantity);
    $stmt->execute();
}
$stmt->close();


unset($_SESSION['cart']);

header("Location: process_order.php?order_id=" . $order_id);
exit;

?>