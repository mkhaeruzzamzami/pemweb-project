<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../koneksi.php';

// Validasi input dasar
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Hanya menerima POST request"]);
    exit;
}

$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$email        = $_POST['email'] ?? '';
$tema         = $_POST['tema'] ?? '';
$judul        = $_POST['judul'] ?? '';
$tanggal      = $_POST['tanggal_pembuatan'] ?? '';
$nama_pembuat = $_POST['nama_pembuat'] ?? '';
$deskripsi    = $_POST['deskripsi'] ?? '';
$gambar       = null;

// 🧠 Cari atau buat user (gunakan prepared statement untuk keamanan)
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
        echo json_encode(["status" => "error", "message" => "Gagal menambahkan user"]);
        exit;
    }
    $user_id = $stmt_insert->insert_id;
}

// Upload gambar jika ada
if (!empty($_FILES['gambar']['name'])) {
    $uploadDir = __DIR__ . "/.././uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // auto-buat folder kalau belum ada
    }

    $fileName = time() . "_" . basename($_FILES["gambar"]["name"]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
        $gambar = $fileName;
    } else {
        echo json_encode(["status" => "error", "message" => "Upload gambar gagal"]);
        exit;
    }
}

// Simpan ke tabel lukisan
$stmt = $conn->prepare("INSERT INTO lukisan (user_id, tema, judul, tanggal_pembuatan, nama_pembuat, deskripsi, gambar, likes) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("issssss", $user_id, $tema, $judul, $tanggal, $nama_pembuat, $deskripsi, $gambar);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Lukisan berhasil disimpan"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
