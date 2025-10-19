<?php


if (!isset($p) || !is_array($p)) {
    return;
}
?>
<div class="product-card" data-product-id="<?= (int)$p['id'] ?>" role="group" aria-label="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>">
  <a class="product-link" href="product.php?id=<?= (int)$p['id'] ?>" title="View <?= htmlspecialchars($p['name'], ENT_QUOTES) ?> details">
    <img class="product-image" src="assets/images/<?= htmlspecialchars($p['image'] ?? 'placeholder.jpg', ENT_QUOTES) ?>"
         alt="<?= htmlspecialchars($p['name'] . (isset($p['description']) ? ', ' . $p['description'] : ''), ENT_QUOTES) ?>"
         loading="lazy">
    <div class="product-info">
      <h3 class="product-title"><?= htmlspecialchars($p['name'], ENT_QUOTES) ?></h3>
      <p class="product-price">$<?= number_format((float)($p['price'] ?? 0), 2) ?></p>
      <p class="product-desc"><?= htmlspecialchars($p['description'] ?? '', ENT_QUOTES) ?></p>
    </div>
  </a>

  <div class="product-actions">
    
       <form class="add-to-cart-form" action="cart.php" method="POST" style="display:inline">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
      <button type="submit" class="btn btn-secondary">Add to Cart</button>
    </form>
  </div>
</div>