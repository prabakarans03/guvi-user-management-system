<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json");
require __DIR__ . '/../vendor/autoload.php';

$mysqlHost = '127.0.0.1';
$mysqlDb = 'guvi_project';
$mysqlUser = 'root';
$mysqlPass = '';

$mysqli = new mysqli($mysqlHost, $mysqlUser, $mysqlPass, $mysqlDb);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['error' => 'MySQL connection failed']);
    exit;
}
$mysqli->set_charset('utf8mb4');

$mongoClient = new MongoDB\Client('mongodb://127.0.0.1:27017');
$mongoDb = $mongoClient->guvi_project;
$profilesCollection = $mongoDb->profiles;

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
]);

function safeJsonResponse(array $data, int $code = 200): void
{
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode($data);
    exit;
}
