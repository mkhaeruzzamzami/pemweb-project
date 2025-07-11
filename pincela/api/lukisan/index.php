<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Hanya menerima POST request"]);
    exit;
}
require_once __DIR__ . '/../koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        require __DIR__ . '/read.php';
        break;
    case 'POST':
        require __DIR__ . '/create.php';
        break;
    case 'PUT':
        require __DIR__ . '/update.php';
        break;
    case 'DELETE':
        require __DIR__ . '/delete.php';
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
        break;
}
?>
