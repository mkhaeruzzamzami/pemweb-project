<?php

// Kalau belum submit form, tampilkan form HTML
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Lukisan</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }
        form {
            max-width: 500px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 12px;
        }
        input, textarea {
            width: 100%;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            border: none;
            background: #3498db;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">🖼️ Form Tambah Lukisan</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="text" name="tema" placeholder="Tema Lukisan" required />
        <input type="text" name="judul" placeholder="Judul Lukisan" required />
        <input type="date" name="tanggal_pembuatan" required />
        <input type="text" name="nama_pembuat" placeholder="Nama Pembuat" required />
        <textarea name="deskripsi" placeholder="Deskripsi Lukisan" rows="5" required></textarea>
        <input type="file" name="gambar" accept="image/*" required />
        <button type="submit">Simpan Lukisan</button>
    </form>
    <a href="/pincela/api/lukisan/index_display.php">balik</a>
</body>
</html>
<?php
exit;
}

// ⛔ Jangan lupa: proses input kalau method POST
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../koneksi.php';

$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$email        = $_POST['email'] ?? '';
$tema         = $_POST['tema'] ?? '';
$judul        = $_POST['judul'] ?? '';
$tanggal      = $_POST['tanggal_pembuatan'] ?? '';
$nama_pembuat = $_POST['nama_pembuat'] ?? '';
$deskripsi    = $_POST['deskripsi'] ?? '';
$gambar       = null;

// 🔍 Cek user
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_id = $result_user->fetch_assoc()['id'];
} else {
    $stmt_insert = $conn->prepare("INSERT INTO users (nama_lengkap, email) VALUES (?, ?)");
    $stmt_insert->bind_param("ss", $nama_lengkap, $email);
    if (!$stmt_insert->execute()) {
        exit("<p style='color:red;'>Gagal menambahkan user: " . $conn->error . "</p>");
    }
    $user_id = $stmt_insert->insert_id;
}

// 📦 Upload gambar
if (!empty($_FILES['gambar']['name'])) {
    $uploadDir = __DIR__ . "/.././uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["gambar"]["name"]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
        $gambar = $fileName;
    } else {
        exit("<p style='color:red;'>Upload gambar gagal.</p>");
    }
}

// 💾 Simpan data lukisan
$stmt = $conn->prepare("INSERT INTO lukisan (user_id, tema, judul, tanggal_pembuatan, nama_pembuat, deskripsi, gambar, likes) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("issssss", $user_id, $tema, $judul, $tanggal, $nama_pembuat, $deskripsi, $gambar);

if ($stmt->execute()) {
    echo "<p style='color:green;'>✅ Lukisan berhasil disimpan!</p>";
    echo "<a href='lihat-read.php'>Lihat Semua Lukisan</a>";
} else {
    echo "<p style='color:red;'>❌ Gagal menyimpan lukisan: " . $conn->error . "</p>";
}
?>
