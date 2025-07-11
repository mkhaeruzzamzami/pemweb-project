<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    echo json_encode(["status" => "error", "message" => "ID lukisan tidak valid"]);
    exit();
}

$stmt = $conn->prepare("SELECT nama, komentar FROM feedback WHERE lukisan_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode([
    "status" => "success",
    "comments" => $comments
]);

$stmt->close();
$conn->close();
?>
