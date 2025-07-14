<?php
require_once __DIR__ . '/../koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$id = intval($input['id'] ?? 0);

if (!$id) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "ID tidak valid"
    ]);
    exit;
}

// Periksa apakah data ada
$stmt = $conn->prepare("SELECT * FROM lukisan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "Data lukisan tidak ditemukan"
    ]);
    exit;
}
$stmt->close();

// Hapus data
$stmt = $conn->prepare("DELETE FROM lukisan WHERE id = ?");
$stmt->bind_param("i", $id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode([
        "status" => "success",
        "message" => "Lukisan berhasil dihapus"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menghapus lukisan"
    ]);
}
?>