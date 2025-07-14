<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];

// Cek kalau `Accept` dari request mengandung HTML (berarti buka di browser)
$isHtml = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html') !== false;

require_once __DIR__ . '/../koneksi.php';

switch ($method) {
    case 'GET':
        if ($isHtml) {
            // Buka dari browser → tampilkan HTML
            ?>
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>API Lukisan</title>
                <style>
                    body {
                        font-family: 'Segoe UI', sans-serif;
                        padding: 20px;
                    }
                    .nav-links {
                        display: flex;
                        gap: 15px;
                        flex-wrap: wrap;
                    }
                    .nav-links a {
                        text-decoration: none;
                        padding: 10px 18px;
                        background: #2563eb;
                        color: white;
                        border-radius: 8px;
                        font-weight: bold;
                        transition: background 0.3s ease;
                    }
                    .nav-links a:hover {
                        background: #1d4ed8;
                    }
                </style>
            </head>
            <body>
                <h1>💾 API Lukisan</h1>
                <p>Silakan pilih aksi di bawah ini:</p>
                <div class="nav-links">
                    <a href="create_form.php">✍️ Tambah Lukisan</a>
                    <a href="read-page.php">📄 Lihat Daftar Lukisan</a>
                    <a href="update_form.php">✏️ Update</a>
                    <a href="delete_form.php">🗑️ Hapus</a>
                    <a href="feedback_like_display.php">feedback</a>
                </div>
            </body>
            </html>
            <?php
        } else {
            // Request dari fetch (React) → kirim JSON
            require __DIR__ . '/read.php';
        }
        break;

    case 'POST':
        require __DIR__ . '/create.php';
        break;

    case 'PUT':
        require __DIR__ . '/update.php';
        break;

    case 'DELETE':
        require __DIR__ . '/delete.php';
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
        break;
}
?>