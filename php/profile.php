<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json");
require __DIR__ . '/db.php';

$token = trim($_REQUEST['token'] ?? '');
if (!$token) {
    safeJsonResponse(['success' => false, 'error' => 'Missing auth token.'], 401);
}

$sessionKey = "session:$token";
$userId = $redis->get($sessionKey);
if (!$userId) {
    safeJsonResponse(['success' => false, 'error' => 'Invalid or expired session.'], 401);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim($_POST['action'] ?? '') === 'logout') {
        $redis->del($sessionKey);
        safeJsonResponse(['success' => true]);
    }

    $age = trim($_POST['age'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $updateResult = $profilesCollection->updateOne(
        ['user_id' => (int) $userId],
        ['$set' => [
            'age' => $age ?: null,
            'dob' => $dob ?: null,
            'contact' => $contact ?: null,
            'address' => $address ?: null,
            'updated_at' => new MongoDB\BSON\UTCDateTime(),
        ]],
        ['upsert' => true]
    );

    if ($updateResult->getModifiedCount() || $updateResult->getUpsertedCount()) {
        safeJsonResponse(['success' => true]);
    }
    safeJsonResponse(['success' => false, 'error' => 'No changes were saved.']);
}

$stmt = $mysqli->prepare('SELECT name, email FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($name, $email);
if (!$stmt->fetch()) {
    $stmt->close();
    safeJsonResponse(['success' => false, 'error' => 'User not found.'], 404);
}
$stmt->close();

$profile = $profilesCollection->findOne(['user_id' => (int) $userId]) ?? [];

$response = [
    'success' => true,
    'profile' => [
        'name' => $name,
        'email' => $email,
        'age' => $profile['age'] ?? null,
        'dob' => $profile['dob'] ?? null,
        'contact' => $profile['contact'] ?? null,
        'address' => $profile['address'] ?? null,
    ],
];

safeJsonResponse($response);
