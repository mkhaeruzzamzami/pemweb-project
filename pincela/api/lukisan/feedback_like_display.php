<?php
require_once __DIR__ . '/../koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$isHtml = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html') !== false;
$method = $_SERVER['REQUEST_METHOD'];
$id = intval($_GET['id'] ?? ($_POST['id'] ?? 0));
$action = '';
$data = [];

// JSON body parsing (for React)
if ($method === 'POST' && strpos($_SERVER["CONTENT_TYPE"] ?? '', 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $data['action'] ?? '';
    $id = intval($data['id'] ?? 0);
}

// Handle HTML form submission
if ($method === 'POST') {
    if (isset($_POST['html_like'])) $action = 'like';
    elseif (isset($_POST['html_comment'])) {
        $action = 'comment';
        $data['nama'] = $_POST['nama'] ?? '';
        $data['komentar'] = $_POST['komentar'] ?? '';
    }
}

// === GET: halaman daftar lukisan (grid HTML)
if ($method === 'GET' && $isHtml && !$id) {
    $result = $conn->query("SELECT * FROM lukisan ORDER BY id DESC");
    $base_url = "http://localhost/pincela/api/uploads/";
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Lukisan</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    h1 { text-align: center; }
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
    .card h3 { margin: 8px 0 4px; }
    .card small { color: gray; }
    .card a { display: inline-block; margin-top: 8px; color: #2563eb; text-decoration: none; }
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
        <small>👍 <?= $row['likes'] ?> Like</small><br>
        <a href="?id=<?= $row['id'] ?>">💬 Lihat Feedback</a>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
<?php
    exit;
}

// === Validasi ID & Ambil data lukisan
if (!$id) {
    http_response_code(400);
    echo $isHtml
        ? "<p style='color:red;'>ID lukisan tidak valid</p>"
        : json_encode(["status" => "error", "message" => "ID lukisan tidak valid"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM lukisan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$lukisan = $res->fetch_assoc();
$stmt->close();

if (!$lukisan) {
    http_response_code(404);
    echo $isHtml
        ? "<p style='color:red;'>Lukisan tidak ditemukan</p>"
        : json_encode(["status" => "error", "message" => "Lukisan tidak ditemukan"]);
    exit;
}

// === Proses LIKE
if ($method === 'POST' && $action === 'like') {
    $stmt = $conn->prepare("UPDATE lukisan SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo $isHtml
        ? "<script>location.href='?id=$id';</script>"
        : json_encode(["status" => "success", "message" => "Like ditambahkan"]);
    exit;
}

// === Proses KOMENTAR
if ($method === 'POST' && $action === 'comment') {
    $nama = trim($data['nama'] ?? '');
    $komentar = trim($data['komentar'] ?? '');

    if ($nama === '' || $komentar === '') {
        http_response_code(400);
        echo $isHtml
            ? "<p style='color:red;'>Nama dan komentar wajib diisi</p>"
            : json_encode(["status" => "error", "message" => "Nama dan komentar wajib diisi"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (lukisan_id, nama, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id, $nama, $komentar);
    $stmt->execute();
    $stmt->close();

    echo $isHtml
        ? "<script>location.href='?id=$id';</script>"
        : json_encode(["status" => "success", "message" => "Komentar disimpan"]);
    exit;
}

// === Tampilkan komentar (HTML)
if ($method === 'GET' && $isHtml && $id) {
    $stmt = $conn->prepare("SELECT nama, komentar FROM feedback WHERE lukisan_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $comments = [];
    while ($row = $res->fetch_assoc()) $comments[] = $row;
    $stmt->close();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Komentar Lukisan</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; }
    .comment { border: 1px solid #ddd; padding: 10px; border-radius: 8px; margin-bottom: 10px; }
    form { margin-top: 20px; }
  </style>
</head>
<body>
  <h2>🖼️ Komentar Lukisan: <?= htmlspecialchars($lukisan['judul']) ?></h2>
  <p><strong>Pembuat:</strong> <?= htmlspecialchars($lukisan['nama_pembuat']) ?></p>
  <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($lukisan['deskripsi'])) ?></p>
  <p>👍 Total Like: <?= $lukisan['likes'] ?></p>

  <form method="post">
    <input type="hidden" name="id" value="<?= $id ?>">
    <button name="html_like" type="submit">❤️ Like</button>
  </form>

  <hr>
  <h3>Komentar</h3>
  <?php if (count($comments) === 0): ?>
    <p><em>Belum ada komentar.</em></p>
  <?php else: ?>
    <?php foreach ($comments as $c): ?>
      <div class="comment">
        <strong><?= htmlspecialchars($c['nama']) ?></strong><br>
        <?= nl2br(htmlspecialchars($c['komentar'])) ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <form method="post">
    <h4>Tambah Komentar</h4>
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="text" name="nama" placeholder="Nama" required style="width:100%; padding:6px"><br><br>
    <textarea name="komentar" rows="3" placeholder="Tulis komentar..." required style="width:100%; padding:6px"></textarea><br>
    <button name="html_comment" type="submit">💬 Kirim Komentar</button>
  </form>

  <br>
  <a href="feedback_like_display.php">⬅️ Kembali ke Daftar Lukisan</a>
</body>
</html>
<?php
    exit;
}

// === Jika JSON: ambil komentar saja
$stmt = $conn->prepare("SELECT nama, komentar FROM feedback WHERE lukisan_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$comments = [];
while ($row = $res->fetch_assoc()) $comments[] = $row;

echo json_encode([
    "status" => "success",
    "comments" => $comments
]);
