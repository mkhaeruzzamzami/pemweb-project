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

$query = "SELECT l.*, u.nama_lengkap FROM lukisan l 
          LEFT JOIN users u ON l.user_id = u.id";
$result = $conn->query($query);

$data = [];
$base_url = "http://localhost/pemweb-project-main/pincela/api/uploads/";

while ($row = $result->fetch_assoc()) {
    $gambar = !empty($row['gambar']) ? $base_url . rawurlencode($row['gambar']) : null;

    $data[] = [
        "id" => $row['id'],
        "user_id" => $row['user_id'],
        "tema" => $row['tema'],
        "judul" => $row['judul'],
        "tanggal_pembuatan" => $row['tanggal_pembuatan'],
        "nama_pembuat" => $row['nama_pembuat'],
        "deskripsi" => $row['deskripsi'],
        "email" => $row['email'],
        "gambar" => $row['gambar'],
        "gambar_url" => $gambar,
        "nama_lengkap" => $row['nama_lengkap'],
        "likes" => $row['likes']
    ];
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>
