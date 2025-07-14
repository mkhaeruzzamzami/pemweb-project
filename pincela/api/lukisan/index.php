<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

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

    case 'OPTIONS':
        http_response_code(200);
        exit;

    default:
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "message" => "Metode tidak didukung"
        ]);
        break;
}
?>
