<?php
include 'koneksi.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$judul = $data['judul'];
$seniman = $data['seniman'];
$tahun = $data['tahun'];
$deskripsi = $data['deskripsi'];
$gambar_url = $data['gambar_url'];
$kategori = $data['kategori'];

$query = "UPDATE lukisan SET 
            judul = '$judul', 
            seniman = '$seniman',
            tahun = '$tahun',
            deskripsi = '$deskripsi',
            gambar_url = '$gambar_url',
            kategori = '$kategori'
          WHERE id = $id";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
?>
