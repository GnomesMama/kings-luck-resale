 <?php
declare(strict_types=1);

$host = 'localhost';
$db   = 'resale-store';
$user = 'root';
$pass = '';
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function validateProductData(array $input): array {
    $errors = [];
    $name = trim((string)($input['name'] ?? ''));
    if ($name === '') $errors[] = 'Name is required';

    $price = $input['price'] ?? null;
    if ($price === null || $price === '') {
        $errors[] = 'Price is required';
    } else {
        $price = (float)$price;
        if ($price < 0) $errors[] = 'Price must be non-negative';
    }

    $description = isset($input['description']) ? trim((string)$input['description']) : null;

    $categories = [];
    if (!empty($input['categories']) && is_array($input['categories'])) {
        foreach ($input['categories'] as $c) {
            $cid = (int)$c;
            if ($cid > 0) $categories[] = $cid;
        }
        $categories = array_values(array_unique($categories));
    }

    return [
        'data' => [
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'categories' => $categories
        ],
        'errors' => $errors
    ];
}

function createProduct(mysqli $conn, array $input): int {
    $v = validateProductData($input);
    if (!empty($v['errors'])) {
        throw new InvalidArgumentException(implode('; ', $v['errors']));
    }
    $d = $v['data'];

    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sds', $d['name'], $d['price'], $d['description']);
        mysqli_stmt_execute($stmt);
        $productId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        if (!empty($d['categories'])) {
            syncProductCategories($conn, $productId, $d['categories']);
        }

        mysqli_commit($conn);
        return $productId;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        throw $e;
    }
}

function updateProduct(mysqli $conn, int $productId, array $input): bool {
    $existing = getProductById($conn, $productId);
    if (!$existing) throw new RuntimeException('Product not found');

    $merged = array_merge($existing, $input);
    $v = validateProductData($merged);
    if (!empty($v['errors'])) throw new InvalidArgumentException(implode('; ', $v['errors']));
    $d = $v['data'];

    mysqli_begin_transaction($conn);
    try {
        $fields = [];
        $params = [];
        $types = '';
        foreach (['name','price','description'] as $col) {
            if (array_key_exists($col, $input)) {
                $fields[] = "$col = ?";
                $params[] = $d[$col];
                $types .= ($col === 'price') ? 'd' : 's';
            }
        }

        if (!empty($fields)) {
            $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
            $params[] = $productId;
            $types .= 'i';

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        if (array_key_exists('categories', $input)) {
            syncProductCategories($conn, $productId, $d['categories']);
        }

        mysqli_commit($conn);
        return true;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        throw $e;
    }
}

function deleteProduct(mysqli $conn, int $productId): bool {
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getProductById(mysqli $conn, int $productId): ?array {
    $stmt = mysqli_prepare($conn, "SELECT id, name, price, description FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    if (!$product) return null;

    $stmt = mysqli_prepare($conn, "SELECT c.id, c.name FROM categories c
                                   JOIN product_categories pc ON pc.category_id = c.id
                                   WHERE pc.product_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product['categories'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $product;
}

function syncProductCategories(mysqli $conn, int $productId, array $categoryIds): void {
    $categoryIds = array_values(array_unique(array_map('intval', $categoryIds)));

    $stmt = mysqli_prepare($conn, "DELETE FROM product_categories WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (empty($categoryIds)) return;

    $stmt = mysqli_prepare($conn, "INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
    foreach ($categoryIds as $cid) {
        mysqli_stmt_bind_param($stmt, 'ii', $productId, $cid);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
}


$category = isset($_GET['category']) && (int)$_GET['category'] > 0 ? (int)$_GET['category'] : null;

if ($category) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM products WHERE category_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    $result = mysqli_query($conn, 'SELECT * FROM products');
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}