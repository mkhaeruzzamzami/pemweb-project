<?php
include "../../koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ==== POST: TAMBAH LUKISAN ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $email = $_POST['email'] ?? '';
    $tema = $_POST['tema'] ?? '';
    $judul = $_POST['judul'] ?? '';
    $tanggal = $_POST['tanggal_pembuatan'] ?? '';
    $nama_pembuat = $_POST['nama_pembuat'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $originalName = basename($_FILES['gambar']['name']);
        $sanitizedName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);
        $gambarName = time() . "_" . $sanitizedName;

        $uploadDir = "../../uploads/";
        $uploadPath = $uploadDir . $gambarName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
            $gambar = $gambarName;
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal upload gambar"]);
            exit;
        }
    }

    // Cari user dulu
    $sql_user = "SELECT id FROM users WHERE nama_lengkap = ? AND email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ss", $nama_lengkap, $email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($row_user = $result_user->fetch_assoc()) {
        $user_id = $row_user['id'];
    } else {
        $sql_insert_user = "INSERT INTO users (nama_lengkap, email) VALUES (?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param("ss", $nama_lengkap, $email);
        $stmt_insert_user->execute();
        $user_id = $conn->insert_id;
    }

    // Simpan lukisan
    $sql = "INSERT INTO lukisan (user_id, tema, judul, tanggal_pembuatan, email, nama_pembuat, deskripsi, gambar)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $tema, $judul, $tanggal, $email, $nama_pembuat, $deskripsi, $gambar);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Lukisan berhasil ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    exit;
}

// ==== GET: AMBIL SEMUA LUKISAN ====
$sql = "SELECT l.*, u.nama_lengkap AS uploader_nama, u.email AS uploader_email
        FROM lukisan l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.id DESC";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $row['gambar_url'] = !empty($row['gambar'])
        ? "http://localhost/pincela/uploads/" . rawurlencode($row['gambar'])
        : null;
    $data[] = $row;
}

echo json_encode($data);
