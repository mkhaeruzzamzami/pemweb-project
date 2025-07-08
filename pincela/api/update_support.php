<?php
include "koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tema = $_POST['tema'];
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal_pembuatan'];
    $email = $_POST['email'];
    $nama_pembuat = $_POST['nama_pembuat'];
    $deskripsi = $_POST['deskripsi'];

    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambarName = time() . "_" . $_FILES['gambar']['name'];
        $uploadPath = "../uploads/" . $gambarName;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath);
        $gambar = $gambarName;

        $sql = "UPDATE lukisan SET nama_lengkap=?, tema=?, judul=?, tanggal_pembuatan=?, email=?, nama_pembuat=?, deskripsi=?, gambar=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $nama_lengkap, $tema, $judul, $tanggal, $email, $nama_pembuat, $deskripsi, $gambar, $id);
    } else {
        $sql = "UPDATE lukisan SET nama_lengkap=?, tema=?, judul=?, tanggal_pembuatan=?, email=?, nama_pembuat=?, deskripsi=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssi", $nama_lengkap, $tema, $judul, $tanggal, $email, $nama_pembuat, $deskripsi, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
?>
