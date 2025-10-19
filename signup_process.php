<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}


require_once __DIR__ . '/includes/db_connect.php';


if (!isset($conn) || !($conn instanceof mysqli)) {
    http_response_code(500);
    exit('Database connection not available');
}


$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['password_confirm'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    exit('Missing required fields');
}
if ($password !== $confirm) {
    exit('Passwords do not match');
}


$sql = 'SELECT id FROM users WHERE email = ? LIMIT 1';
$stmt = $conn->prepare($sql);
if (!$stmt) { exit('Prepare failed: ' . $conn->error); }
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    exit('Email already registered');
}
$stmt->close();


$hash = password_hash($password, PASSWORD_DEFAULT);
$insertSql = 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)';
$ins = $conn->prepare($insertSql);
if (!$ins) { exit('Prepare failed: ' . $conn->error); }
$ins->bind_param('sss', $username, $email, $hash);
$ok = $ins->execute();
if ($ok) {
    $ins->close();
    header('Location: index.php?signup=success');
    exit;
} else {
    $err = $ins->error;
    $ins->close();
    exit('Registration failed: ' . $err);
}