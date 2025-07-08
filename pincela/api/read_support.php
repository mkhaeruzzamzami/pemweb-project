<?php 
include "koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$query = "SELECT * FROM lukisan ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Tambahkan pengecekan kalau gambar NULL atau kosong
    $row['gambar_url'] = (!empty($row['gambar']))
        ? "http://localhost/pincela/uploads/" . rawurlencode($row['gambar'])
        : null;

    $data[] = $row;
}

echo json_encode($data);
?>
