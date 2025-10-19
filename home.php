<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
?>

<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <title>Home - Kings Luck Second Chance Resale</title>
    <link rel="stylesheet" href="assets/styles.css">
    
</head>
<body>
    <header>
        <h1>Kings Luck Second Chance Resale</h1>
        <?php
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']);
}
?>
<nav>
  <a href="home.php">Home</a> 
  <a href="cart.php">Cart (<?= (int)$cartCount ?>)</a>
  <?php if (!empty($_SESSION['id'])): ?>
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="index.php">Login</a>
    <a href="signup.php">Sign Up</a>
  <?php endif; ?>
  <?php
require_once __DIR__ . '/includes/db_connect.php';

$catStmt = $conn->query("SELECT id, name FROM categories ORDER BY sort_order, name");
$categories = $catStmt->fetch_all(MYSQLI_ASSOC);

$currentCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
?>
<form method="get" action="products.php" aria-label="Filter products by category">
  <label for="category-select">Category</label>
  <select id="category-select" name="category">
    
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int)$c['id'] ?>" <?= $currentCategory === (int)$c['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['name'], ENT_QUOTES) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn">Filter</button>
</form>

</nav>

    </header>

    <main>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <div class="search-bar">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search for items...">
            <button type="submit">Search</button>
            </form>
        </div>

        <section class="product-grid">
           <!-- Product card 1 -->
<div class="product-card" data-product-id="1" role="group" aria-label="New Kids On The Block Concert Tee">
  <a class="product-link" href="/product.php?id=1" title="View New Kids On The Block Concert Tee details">
    <img class="product-image" src="assets/images/sample-item.jpg" alt="New Kids On The Block Concert Tee, gently used, size S" loading="lazy">
    <div class="product-info">
      <h3 class="product-title">New Kids On The Block Concert Tee</h3>
      <p class="product-price">$26.00</p>
      <p class="product-desc">Gently used, size S</p>
    </div>
  </a>
<form method="POST" action="cart.php">
  <input type="hidden" name="action" value="add"> 
  <input type="hidden" name="product_id" value="1">
  <button class="btn btn-secondary" type="submit">Add to Cart</button>
</form>

</div>

<!-- Product card 2 -->
<div class="product-card" data-product-id="2" role="group" aria-label="stainless steel bottle 750ml">
  <a class="product-link" href="/product.php?id=2" title="View stainless steel bottle 750ml details">
    <img class="product-image" src="assets/images/stainless-bottle-750ml.jpg" alt="Stainless steel bottle 750ml, double-wall insulated" loading="lazy">
    <div class="product-info">
      <h3 class="product-title">stainless steel bottle 750ml</h3>
      <p class="product-price">$20.00</p>
      <p class="product-desc">Double-wall insulated bottle, keeps drinks cold or hot</p>
    </div>
  </a>
<form method="POST" action="cart.php">
  <input type="hidden" name="product_id" value="2">
  <input type="hidden" name="action" value="add"> 
  <button class="btn btn-secondary" type="submit">Add to Cart</button>
</form>
</div>

<!-- Product card 3 -->
<div class="product-card" data-product-id="3" role="group" aria-label="Bamboo Cutting Board Large">
  <a class="product-link" href="/product.php?id=3" title="View Bamboo Cutting Board Large details">
    <img class="product-image" src="assets/images/bamboo-cutting-board-large.jpg" alt="Bamboo Cutting Board Large, durable with juice groove" loading="lazy">
    <div class="product-info">
      <h3 class="product-title">Bamboo Cutting Board Large</h3>
      <p class="product-price">$22.00</p>
      <p class="product-desc">Durable bamboo board, juice groove, dishwasher-safe</p>
    </div>
  </a>
<form method="POST" action="/cart.php">
  <input type="hidden" name="product_id" value="3">
  <input type="hidden" name="action" value="add"> 
  <button class="btn btn-secondary" type="submit">Add to Cart</button>
</form>
</div>

<!-- Product card 4 -->
<div class="product-card" data-product-id="4" role="group" aria-label="Ceramic Mug 12oz">
  <a class="product-link" href="/product.php?id=4" title="View Ceramic Mug 12oz details">
    <img class="product-image" src="assets/images/ceramic-mug-12oz.jpg" alt="Ceramic Mug 12oz, glazed, microwave and dishwasher safe" loading="lazy">
    <div class="product-info">
      <h3 class="product-title">Ceramic Mug 12oz</h3>
      <p class="product-price">$9.95</p>
      <p class="product-desc">Glazed ceramic mug, microwave and dishwasher safe</p>
    </div>
  </a>
<form method="POST" action="cart.php">
  <input type="hidden" name="product_id" value="4">
  <input type="hidden" name="action" value="add"> 
  <button class="btn btn-secondary" type="submit">Add to Cart</button>
</form>
</div>

<!-- Product card 5 -->
<div class="product-card" data-product-id="5" role="group" aria-label="Organic Cotton Sheet Set Queen">
  <a class="product-link" href="/product.php?id=5" title="View Organic Cotton Sheet Set Queen details">
    <img class="product-image" src="assets/images/organic-sheet-set-queen.jpg" alt="Organic Cotton Sheet Set Queen, 300-thread-count, deep pockets" loading="lazy">
    <div class="product-info">
      <h3 class="product-title">Organic Cotton Sheet Set Queen</h3>
      <p class="product-price">$120.00</p>
      <p class="product-desc">300-thread-count, breathable organic cotton, deep pockets</p>
    </div>
  </a>
<form method="POST" action="cart.php">
  <input type="hidden" name="product_id" value="5">
  <input type="hidden" name="action" value="add"> 
  <button class="btn btn-secondary" type="submit">Add to Cart</button>
</form>
</div>

<!-- Product card 6 -->
<div class="product-card" data-product-id="6" role="group" aria-label="Yoga Mat 6mm">
  <a class="product-link" href="/product.php?id=6" title="View Yoga Mat 6mm details">
    <img class="product-image" src="assets/images/yoga-mat-6mm.jpg" alt="Yoga Mat 6mm, non-slip surface, lightweight" loading="lazy">
    <div class="product-info">
      <h3 class="product-title">Yoga Mat 6mm</h3>
      <p class="product-price">$35.00</p>
      <p class="product-desc">Non-slip surface, lightweight, easy to clean</p>
    </div>
  </a>
<form method="POST" action="/resale-store/cart.php">
  <input type="hidden" name="product_id" value="6">
  <input type="hidden" name="action" value="add"> 
  <button class="btn btn-secondary" type="submit">Add to Cart</button>
</form>
</div>
            
            
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Kings Luck Second Chance Resale</p>
            </footer>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit();
}
?>
