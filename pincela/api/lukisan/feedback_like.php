<?php
require_once __DIR__ . '/../koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Ambil data input JSON (POST)
$inputData = json_decode(file_get_contents("php://input"), true);

// Cek ID lukisan
$id = intval($_GET['id'] ?? ($inputData['id'] ?? 0));
if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID lukisan tidak valid"]);
    exit;
}

// Pastikan lukisan tersedia
$stmt = $conn->prepare("SELECT * FROM lukisan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$lukisan = $res->fetch_assoc();
$stmt->close();

if (!$lukisan) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Lukisan tidak ditemukan"]);
    exit;
}

// === LIKE ===
if ($method === 'POST' && ($inputData['action'] ?? '') === 'like') {
    $stmt = $conn->prepare("UPDATE lukisan SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["status" => "success", "message" => "Like ditambahkan"]);
    exit;
}

// === COMMENT ===
if ($method === 'POST' && ($inputData['action'] ?? '') === 'comment') {
    $nama = trim($inputData['nama'] ?? '');
    $komentar = trim($inputData['komentar'] ?? '');

    if ($nama === '' || $komentar === '') {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Nama dan komentar wajib diisi"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (lukisan_id, nama, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id, $nama, $komentar);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["status" => "success", "message" => "Komentar disimpan"]);
    exit;
}

// === GET COMMENT ===
if ($method === 'GET') {
    $stmt = $conn->prepare("SELECT nama, komentar FROM feedback WHERE lukisan_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    $comments = [];
    while ($row = $res->fetch_assoc()) $comments[] = $row;

    echo json_encode([
        "status" => "success",
        "comments" => $comments
    ]);
    exit;
}

// === DEFAULT ===
http_response_code(405);
echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
?>