<?php
$conn = mysqli_connect("localhost", "root", "", "pemweb");

if (!$conn) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Koneksi ke database gagal"]);
    exit;
}
?>
