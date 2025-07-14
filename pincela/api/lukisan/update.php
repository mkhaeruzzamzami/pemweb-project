<?php
require_once __DIR__ . '/../koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Cek method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
    exit;
}

$id = intval($_POST['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID tidak valid"]);
    exit;
}

// Inisialisasi variabel update
$fields = [];
$params = [];
$types  = "";

// Field yang boleh diupdate
$allowedFields = [
    "nama_lengkap", "email", "tema", "judul",
    "tanggal_pembuatan", "nama_pembuat", "deskripsi"
];

foreach ($allowedFields as $field) {
    if (!empty($_POST[$field])) {
        $fields[] = "$field = ?";
        $params[] = trim($_POST[$field]);
        $types   .= "s";
    }
}

// Handle upload gambar jika ada
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = uniqid() . '_' . basename($_FILES['gambar']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
        $fields[] = "gambar = ?";
        $params[] = $filename;
        $types   .= "s";
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal upload gambar"]);
        exit;
    }
}

// Jika tidak ada data dikirim
if (empty($fields)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Tidak ada data yang dikirim"]);
    exit;
}

// Tambahkan ID ke akhir query
$params[] = $id;
$types .= "i";

$query = "UPDATE lukisan SET " . implode(', ', $fields) . " WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Query gagal: " . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Lukisan berhasil diupdate"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal update: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
