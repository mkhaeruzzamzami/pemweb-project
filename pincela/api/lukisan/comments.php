<?php
include "../../koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$lukisan_id = $_GET['id'] ?? 0;

$sql = "SELECT nama, komentar, tanggal FROM feedback WHERE lukisan_id = ? ORDER BY tanggal DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lukisan_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode($comments);
?>
