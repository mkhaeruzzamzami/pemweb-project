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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Metode tidak diizinkan"
    ]);
    exit;
}

// Ambil data dari FormData
$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$email        = $_POST['email'] ?? '';
$tema         = $_POST['tema'] ?? '';
$judul        = $_POST['judul'] ?? '';
$tanggal      = $_POST['tanggal_pembuatan'] ?? '';
$nama_pembuat = $_POST['nama_pembuat'] ?? '';
$deskripsi    = $_POST['deskripsi'] ?? '';
$gambar       = null;

// Validasi dasar
if (!$nama_lengkap || !$email || !$tema || !$judul || !$tanggal || !$nama_pembuat || !$deskripsi) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Semua field wajib diisi"
    ]);
    exit;
}

// 🔍 Cek user by email
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_id = $result_user->fetch_assoc()['id'];
} else {
    $stmt_insert = $conn->prepare("INSERT INTO users (nama_lengkap, email) VALUES (?, ?)");
    $stmt_insert->bind_param("ss", $nama_lengkap, $email);
    if (!$stmt_insert->execute()) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menambahkan user"
        ]);
        exit;
    }
    $user_id = $stmt_insert->insert_id;
}

// 📦 Upload gambar
if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/../uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["gambar"]["name"]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
        $gambar = $fileName;
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Upload gambar gagal"
        ]);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Gambar wajib diupload"
    ]);
    exit;
}

// 💾 Simpan ke tabel lukisan
$stmt = $conn->prepare("INSERT INTO lukisan (user_id, tema, judul, tanggal_pembuatan, nama_pembuat, deskripsi, gambar, likes) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("issssss", $user_id, $tema, $judul, $tanggal, $nama_pembuat, $deskripsi, $gambar);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Lukisan berhasil disimpan"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan lukisan: " . $conn->error
    ]);
}
?>
