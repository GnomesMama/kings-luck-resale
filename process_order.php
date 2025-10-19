<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_GET['order_id'])) {
    die("Missing order ID.");
}

$order_id = intval($_GET['order_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shipping & Payment</title>
</head>
<body>
    <h2>Order #<?= $order_id ?>: Shipping & Payment</h2>

    <form action="finalize_order.php" method="post">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">

        <h3>Shipping Info</h3>
        <label>Name: <input type="text" name="shipping_name" required></label><br>
        <label>Address: <input type="text" name="shipping_address" required></label><br>
        <label>City: <input type="text" name="shipping_city" required></label><br>
        <label>ZIP: <input type="text" name="shipping_zip" required></label><br>

        <h3>Card Info</h3>
        <label>Card Number: <input type="text" name="card_number" required></label><br>
        <label>Expiration: <input type="text" name="card_expiry" required></label><br>
        <label>CVV: <input type="text" name="card_cvv" required></label><br>

        <button type="submit">Submit Payment</button>
    </form>
</body>
</html>