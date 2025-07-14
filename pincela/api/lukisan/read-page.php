<?php
require_once __DIR__ . '/../koneksi.php';

$query = "SELECT * FROM lukisan ORDER BY id DESC";
$result = $conn->query($query);

$base_url = "http://localhost/pincela/api/uploads/";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Lukisan</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
    }
    h1 {
      text-align: center;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 16px;
      margin-top: 20px;
    }
    .card {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .card img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }
    .card h3 {
      margin: 8px 0 4px;
    }
    .card small {
      color: gray;
    }
  </style>
</head>
<body>
  <a href="/pincela/api/lukisan/index_display.php">⬅️ Kembali ke Halaman Utama</a>
  <h1>🎨 Daftar Lukisan</h1>
  <div class="grid">
    <?php while ($row = $result->fetch_assoc()): 
      $gambar_url = !empty($row['gambar']) ? $base_url . rawurlencode($row['gambar']) : null;
    ?>
      <div class="card">
        <?php if ($gambar_url): ?>
          <img src="<?= htmlspecialchars($gambar_url) ?>" alt="Lukisan">
        <?php else: ?>
          <p><em>Tanpa gambar</em></p>
        <?php endif; ?>
        <h3><?= htmlspecialchars($row['judul']) ?></h3>
        <p><strong>Tema:</strong> <?= htmlspecialchars($row['tema']) ?></p>
        <p><strong>Pembuat:</strong> <?= htmlspecialchars($row['nama_pembuat']) ?></p>
        <p><strong>Tanggal:</strong> <?= htmlspecialchars($row['tanggal_pembuatan']) ?></p>
        <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
        <small>👍 <?= $row['likes'] ?> Like</small>
      </div>
    <?php endwhile; ?>
  </div>
  <br>
</body>
</html>
