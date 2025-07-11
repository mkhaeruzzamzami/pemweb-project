<?php
// Lokasi: \pemweb-project\pincela\api\lukisan\feedback_like.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

require_once __DIR__ . '/../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil komentar berdasarkan id lukisan
    $id = intval($_GET['id'] ?? 0);

    if (!$id) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID lukisan tidak valid"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT nama, komentar FROM feedback WHERE lukisan_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            "user" => $row["nama"],
            "text" => $row["komentar"],
        ];
    }

    echo json_encode(["status" => "success", "comments" => $comments]);
    exit();
}

// ================================================
// HANDLE POST REQUEST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$id     = intval($data['id'] ?? 0);

if (!$id || !in_array($action, ['like', 'comment'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
    exit();
}

// Cek apakah lukisan ada
$stmt_check = $conn->prepare("SELECT id FROM lukisan WHERE id = ?");
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$stmt_check->close();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Lukisan tidak ditemukan"]);
    exit();
}

// 👍 Like
if ($action === "like") {
    $stmt = $conn->prepare("UPDATE lukisan SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Like berhasil disimpan"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    $stmt->close();
}

// 💬 Comment
elseif ($action === "comment") {
    $nama     = $data['nama'] ?? '';
    $komentar = $data['komentar'] ?? '';

    if (trim($nama) === '' || trim($komentar) === '') {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Nama dan komentar wajib diisi"]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO feedback (lukisan_id, nama, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id, $nama, $komentar);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Komentar berhasil disimpan"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    $stmt->close();
}

$conn->close();
