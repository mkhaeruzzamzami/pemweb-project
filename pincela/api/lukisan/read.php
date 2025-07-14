<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["status" => "error", "message" => "Hanya menerima GET request"]);
    exit;
}

require_once __DIR__ . '/../koneksi.php';

$query = "SELECT * FROM lukisan ORDER BY id DESC";
$result = $conn->query($query);

$data = [];
$base_url = "http://localhost/pincela/api/uploads/";

while ($row = $result->fetch_assoc()) {
    $row['gambar_url'] = !empty($row['gambar']) ? $base_url . rawurlencode($row['gambar']) : null;
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "message" => "Data lukisan berhasil diambil",
    "data" => $data
]);
?>
