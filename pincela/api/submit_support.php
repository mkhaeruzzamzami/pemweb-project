<?php
ob_start();
error_reporting(0);
include "koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $tema = $_POST['tema'] ?? '';
    $judul = $_POST['judul'] ?? '';
    $tanggal = $_POST['tanggal_pembuatan'] ?? '';
    $email = $_POST['email'] ?? '';
    $nama_pembuat = $_POST['nama_pembuat'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $originalName = basename($_FILES['gambar']['name']);
        $sanitizedName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);
        $gambarName = time() . "_" . $sanitizedName;

        $uploadDir = "../uploads/";
        $uploadPath = $uploadDir . $gambarName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
            $gambar = $gambarName;
        } else {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "Gagal upload gambar"]);
            exit;
        }
    }

    $sql = "INSERT INTO lukisan (nama_lengkap, tema, judul, tanggal_pembuatan, email, nama_pembuat, deskripsi, gambar)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", $nama_lengkap, $tema, $judul, $tanggal, $email, $nama_pembuat, $deskripsi, $gambar);

    if (mysqli_stmt_execute($stmt)) {
        ob_end_clean();
        echo json_encode(["status" => "success"]);
    } else {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
