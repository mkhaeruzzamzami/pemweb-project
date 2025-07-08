<?php
include "koneksi.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Tambahkan jika perlu untuk CORS
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");

// Ambil body JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validasi awal
if (!isset($data["id"]) || !isset($data["comment"])) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit;
}

$id = $data["id"];
$comment = $data["comment"]; // langsung array, jangan json_encode dulu

if (!$id || !is_array($comment)) {
    echo json_encode(["status" => "error", "message" => "ID atau komentar tidak valid"]);
    exit;
}

// Ambil komentar lama
$sql = "SELECT comments FROM lukisan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Validasi jika tidak ditemukan
if (!$row) {
    echo json_encode(["status" => "error", "message" => "Lukisan tidak ditemukan"]);
    exit;
}

$existing_comments = json_decode($row["comments"], true) ?? [];
$existing_comments[] = $comment;
$new_comments_json = json_encode($existing_comments);

// Update komentar baru
$sql = "UPDATE lukisan SET comments = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_comments_json, $id);
$stmt->execute();

echo json_encode(["status" => "success", "message" => "Komentar berhasil ditambahkan"]);
?>
