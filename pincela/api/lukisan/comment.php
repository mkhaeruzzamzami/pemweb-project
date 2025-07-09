<?php
include "../../koneksi.php"; // disesuaikan dengan struktur kamu

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"]) || !isset($data["comment"]["nama"]) || !isset($data["comment"]["komentar"])) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

$lukisan_id = $data["id"];
$nama = $data["comment"]["nama"];
$komentar = $data["comment"]["komentar"];

$sql = "INSERT INTO feedback (lukisan_id, nama, komentar) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $lukisan_id, $nama, $komentar);
$stmt->execute();

echo json_encode(["status" => "success", "message" => "Komentar berhasil ditambahkan"]);
?>
