<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json");
require __DIR__ . '/db.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$name || !$email || !$password) {
    safeJsonResponse(['success' => false, 'error' => 'All fields are required.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    safeJsonResponse(['success' => false, 'error' => 'Invalid email.'], 400);
}

$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    safeJsonResponse(['success' => false, 'error' => 'Email is already registered.'], 409);
}
$stmt->close();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$insert = $mysqli->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
$insert->bind_param('sss', $name, $email, $passwordHash);
if (!$insert->execute()) {
    safeJsonResponse(['success' => false, 'error' => 'Failed to create user.'], 500);
}
$userId = $insert->insert_id;
$insert->close();

$profilesCollection->insertOne([
    'user_id' => $userId,
    'name' => $name,
    'email' => $email,
    'age' => null,
    'dob' => null,
    'contact' => null,
    'address' => null,
    'updated_at' => new MongoDB\BSON\UTCDateTime(),
]);

safeJsonResponse(['success' => true]);
