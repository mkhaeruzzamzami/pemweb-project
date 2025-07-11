<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pincela";

// ✅ Gunakan mysqli_connect
$conn = mysqli_connect($host, $user, $pass, $db);

// ❗ Jangan pakai $conn->connect_error (itu hanya untuk `new mysqli`)
if (!$conn) {
    die(json_encode([
        "status" => "error",
        "message" => "Koneksi DB gagal: " . mysqli_connect_error()
    ]));
}
?>
