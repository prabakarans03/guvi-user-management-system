<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json");
require __DIR__ . '/db.php';

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$email || !$password) {
    safeJsonResponse(['success' => false, 'error' => 'Email and password are required.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    safeJsonResponse(['success' => false, 'error' => 'Invalid email.'], 400);
}

if (strlen($password) < 6) {
    safeJsonResponse(['success' => false, 'error' => 'Weak password.'], 400);
}

$stmt = $mysqli->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($userId, $passwordHash);
if (!$stmt->fetch()) {
    $stmt->close();
    safeJsonResponse(['success' => false, 'error' => 'Invalid credentials.'], 401);
}
$stmt->close();

if (!password_verify($password, $passwordHash)) {
    safeJsonResponse(['success' => false, 'error' => 'Invalid credentials.'], 401);
}

$token = bin2hex(random_bytes(32));
$sessionKey = "session:$token";
$redis->setex($sessionKey, 3600, $userId);

safeJsonResponse(['success' => true, 'token' => $token]);
