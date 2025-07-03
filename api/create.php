<?php
include 'koneksi.php';

$data = json_decode(file_get_contents("php://input"), true);

$judul = $data['judul'];
$seniman = $data['seniman'];
$tahun = $data['tahun'];
$deskripsi = $data['deskripsi'];
$gambar_url = $data['gambar_url'];
$kategori = $data['kategori'];

$query = "INSERT INTO lukisan (judul, seniman, tahun, deskripsi, gambar_url, kategori)
          VALUES ('$judul', '$seniman', '$tahun', '$deskripsi', '$gambar_url', '$kategori')";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
?>
