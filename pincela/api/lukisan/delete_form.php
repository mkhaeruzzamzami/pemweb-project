<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

require_once __DIR__ . '/../koneksi.php';

// Menangani proses DELETE jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';

    if (empty($judul)) {
        echo "<p style='color:red;'>❌ Judul lukisan harus dipilih.</p>";
    } else {
        $stmt = $conn->prepare("DELETE FROM lukisan WHERE judul = ?");
        $stmt->bind_param("s", $judul);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>✅ Lukisan berjudul <strong>$judul</strong> berhasil dihapus.</p>";
        } else {
            echo "<p style='color:red;'>❌ Gagal menghapus lukisan: " . $conn->error . "</p>";
        }
    }
    echo "<a href='index.php'>← Kembali</a><hr>";
}

// Ambil semua judul lukisan
$result = $conn->query("SELECT judul FROM lukisan ORDER BY judul ASC");
$judulList = [];

while ($row = $result->fetch_assoc()) {
    $judulList[] = $row['judul'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Lukisan Berdasarkan Judul</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">🗑️ Hapus Lukisan Berdasarkan Judul</h2>
    <?php if (empty($judulList)) : ?>
        <p style="text-align:center;">Tidak ada data lukisan untuk dihapus.</p>
    <?php else : ?>
        <form method="POST">
            <label for="judul">Pilih Judul Lukisan:</label>
            <select name="judul" id="judul" required>
                <option value="">-- Pilih Judul --</option>
                <?php foreach ($judulList as $judul): ?>
                    <option value="<?= htmlspecialchars($judul) ?>"><?= htmlspecialchars($judul) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Hapus Lukisan</button>
        </form>
    <?php endif; ?>
    <a href="/pincela/api/lukisan/index_display.php">⬅️ Kembali ke Halaman Utama</a>
</body>
</html>
