<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $stmt = mysqli_prepare($conn, 'SELECT id FROM products WHERE id = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 'i', $productId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId] += 1;
                } else {
                    $_SESSION['cart'][$productId] = 1;
                }
            }

            mysqli_stmt_close($stmt);
        }
        header('Location: /resale-store/cart.php');
        exit();
    }

    foreach ($_POST as $key => $value) {
    if (strpos($key, 'action') === 0 && strpos($value, 'remove_') === 0) {
        $productId = (int)str_replace('remove_', '', $value);
        if ($productId > 0) {
            unset($_SESSION['cart'][$productId]);
        }
        header('Location: /resale-store/cart.php');
        exit();
    }
} 

    if ($action === 'update') {
        $quantities = $_POST['quantities'] ?? [];
        if (is_array($quantities)) {
            foreach ($quantities as $pid => $qty) {
                $pid = (int)$pid;
                $qty = max(0, (int)$qty);
                if ($qty === 0) {
                    unset($_SESSION['cart'][$pid]);
                } else {
                    $_SESSION['cart'][$pid] = $qty;
                }
            }
        }
        header('Location: /resale-store/cart.php');
        exit();
    }
}

// Render cart view
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0.0;

if ($cart) {
    $ids = array_keys($cart);
    $ids = array_map('intval', $ids); // sanitize
    $idList = implode(',', $ids);
    $sql = "SELECT id, name, price, image FROM products WHERE id IN ($idList)";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as $r) {
        $items[$r['id']] = $r;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  

  <main>
    <h1>Your Cart</h1>

    <?php if (empty($cart)): ?>
      <p>Your cart is empty. <a href="home.php">Shop now</a>.</p>
    <?php else: ?>
      <form action="/resale-store/cart.php" method="POST">
        <input type="hidden" name="action" value="update">
        <table class="cart-table">
          <thead>
            <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $pid => $qty):
              $p = $items[$pid] ?? null;
              if (!$p) continue;
              $subtotal = $p['price'] * $qty;
              $total += $subtotal;
            ?>
          <tr>
      <td>
    <img src="assets/images/<?= htmlspecialchars($p['image'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>" style="width:60px;height:60px;object-fit:cover">
    <?= htmlspecialchars($p['name'], ENT_QUOTES) ?>
  </td>
  <td>$<?= number_format($p['price'], 2) ?></td>
  <td>
    <input type="number" name="quantities[<?= (int)$pid ?>]" value="<?= (int)$qty ?>" min="0" style="width:70px">
  </td>
  <td>$<?= number_format($subtotal, 2) ?></td>
<button type="submit" name="action" value="remove_<?= (int)$pid ?>" class="btn">Remove</button>
            </td>
          </tr>
  
              
            <?php endforeach; ?>
          </tbody>
        </table>

        <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
        
        <a class="btn btn-primary" href="checkout.php">Proceed to Checkout</a>
        <a class="btn" href="home.php" style="margin-left:10px;">Home</a>

      </form>
    <?php endif; ?>
  </main>


</body>
</html>