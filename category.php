<?php
 declare(strict_types= 1);
function createCategory(mysqli $conn, string $name): int {
    $name = trim($name);
    if ($name === '') throw new InvalidArgumentException('Name is required');

    $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, created_at, updated_at) VALUES (?, NOW(), NOW())");
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);
    $newId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $newId;
}

function updateCategory(mysqli $conn, int $id, string $name): bool {
    $name = trim($name);
    if ($name === '') throw new InvalidArgumentException('Name is required');

    $stmt = mysqli_prepare($conn, "UPDATE categories SET name = ?, updated_at = NOW() WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $name, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function deleteCategory(mysqli $conn, int $id): bool {
    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function getCategoryById(mysqli $conn, int $id): ?array {
    $stmt = mysqli_prepare($conn, "SELECT id, name, created_at, updated_at FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

function listCategories(mysqli $conn): array {
    $result = mysqli_query($conn, "SELECT id, name, created_at, updated_at FROM categories ORDER BY name ASC");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function countProductsInCategory(mysqli $conn, int $categoryId): int {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM product_categories WHERE category_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $categoryId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count;
}