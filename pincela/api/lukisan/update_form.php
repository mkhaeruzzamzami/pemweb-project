<?php
require_once __DIR__ . '/../koneksi.php';

// 🔁 Jika form disubmit → proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if (!$id) {
        echo "<p style='color:red;'>❌ ID lukisan tidak valid.</p><a href='index_display.php'>⬅️ Kembali</a>";
        exit;
    }

    // Daftar kolom yang bisa diupdate
    $allowedFields = [
        "nama_lengkap", "email", "tema", "judul",
        "tanggal_pembuatan", "nama_pembuat", "deskripsi"
    ];

    $fields = [];
    $params = [];
    $types  = "";

    foreach ($allowedFields as $field) {
        $value = trim($_POST[$field] ?? '');
        if ($value !== '') {
            $fields[] = "$field = ?";
            $params[] = $value;
            $types   .= "s";
        }
    }

    // Handle gambar jika diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = uniqid() . '_' . basename($_FILES['gambar']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            $fields[] = "gambar = ?";
            $params[] = $filename;
            $types   .= "s";
        } else {
            echo "<p style='color:red;'>❌ Gagal mengupload gambar.</p><a href='index_display.php'>⬅️ Kembali</a>";
            exit;
        }
    }

    if (empty($fields)) {
        echo "<p style='color:red;'>❌ Tidak ada data yang dikirim untuk diupdate.</p><a href='index_display.php'>⬅️ Kembali</a>";
        exit;
    }

    $params[] = $id;
    $types   .= "i";

    $sql = "UPDATE lukisan SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "<p style='color:red;'>❌ Query error: " . htmlspecialchars($conn->error) . "</p><a href='index_display.php'>⬅️ Kembali</a>";
        exit;
    }

    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        echo "<script>alert('✅ Lukisan berhasil diupdate!'); window.location.href='index_display.php';</script>";
    } else {
        echo "<p style='color:red;'>❌ Gagal update lukisan.</p><a href='index_display.php'>⬅️ Kembali</a>";
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Ambil daftar lukisan
$result = $conn->query("SELECT * FROM lukisan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Update Lukisan (HTML)</title>
  <style>
    body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: auto; }
    form { margin-top: 20px; border: 1px solid #ccc; padding: 20px; border-radius: 10px; }
    input, textarea, select { width: 100%; padding: 8px; margin-top: 10px; }
    button { margin-top: 12px; padding: 10px 16px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; }
  </style>
</head>
<body>

<h2>🖼️ Update Data Lukisan (Form Manual)</h2>

<form method="POST" action="update_form.php" enctype="multipart/form-data">
  <label for="id">Pilih Lukisan:</label>
  <select name="id" id="id" required>
    <option value="">-- Pilih Judul --</option>
    <?php while ($row = $result->fetch_assoc()): ?>
      <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['judul']) ?></option>
    <?php endwhile; ?>
  </select>

  <label>Nama Lengkap:</label>
  <input type="text" name="nama_lengkap" placeholder="Kosongkan jika tidak diubah">

  <label>Email:</label>
  <input type="email" name="email" placeholder="Kosongkan jika tidak diubah">

  <label>Tema:</label>
  <input type="text" name="tema" placeholder="Kosongkan jika tidak diubah">

  <label>Judul:</label>
  <input type="text" name="judul" placeholder="Kosongkan jika tidak diubah">

  <label>Tanggal Pembuatan:</label>
  <input type="date" name="tanggal_pembuatan">

  <label>Nama Pembuat:</label>
  <input type="text" name="nama_pembuat" placeholder="Kosongkan jika tidak diubah">

  <label>Deskripsi:</label>
  <textarea name="deskripsi" rows="4" placeholder="Kosongkan jika tidak diubah"></textarea>

  <label>Upload Gambar (opsional):</label>
  <input type="file" name="gambar" accept="image/*">

  <button type="submit">Update Lukisan</button>
</form>

<a href="index_display.php" style="display:inline-block; margin-top: 20px;">⬅️ Kembali ke Beranda</a>

</body>
</html>
