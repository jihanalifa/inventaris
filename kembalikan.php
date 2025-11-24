<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) die('Barang tidak ditemukan.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peminjam = trim($_POST['peminjam']);
    $jumlah = (int)$_POST['jumlah'];
    $catatan = trim($_POST['catatan']);

    if ($peminjam === '' || $jumlah <= 0) $error = 'Isi peminjam dan jumlah dengan benar.';
    else {
        // insert transaksi kembali
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'kembali', ?, ?)");
        $stmt->bind_param('isis', $id, $peminjam, $jumlah, $catatan);
        if ($stmt->execute()) {
            // tambahkan tersedia (tidak boleh melebihi jumlah total)
            $stmt2 = $mysqli->prepare("UPDATE barang SET tersedia = LEAST(jumlah, tersedia + ?) WHERE id = ?");
            $stmt2->bind_param('ii', $jumlah, $id);
            $stmt2->execute();
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menyimpan: " . $mysqli->error;
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kembalikan Barang</title>
</head>
<style>
    /* --- Reused Container Styling --- */
.container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #FFFFFF;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Judul Form */
.container h2 {
    color: #6A1B9A; /* Ungu Gelap untuk judul */
    margin-bottom: 25px;
    font-size: 24px;
    text-align: center;
}

/* Pesan Error */
p.error {
    color: #FF1744;
    background-color: #FFCDD2;
    border: 1px solid #FF8A80;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: left;
}

/* Label */
.container label {
    display: block;
    text-align: left;
    margin-top: 10px;
    margin-bottom: 5px;
    color: #4A148C; /* Ungu lebih gelap */
    font-weight: bold;
}

/* Input Fields (Text, Number) dan Textarea */
.container input[type="text"],
.container input[type="number"],
.container textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 2px solid #D1C4E9; /* Border ungu muda */
    border-radius: 6px;
    box-sizing: border-box;
    transition: border-color 0.3s;
    font-size: 14px;
    resize: vertical;
}

.container textarea {
    min-height: 80px;
}

/* Fokus State */
.container input[type="text"]:focus,
.container input[type="number"]:focus,
.container textarea:focus {
    border-color: #6A1B9A; /* Ungu Gelap saat fokus */
    outline: none;
}

/* Tombol Kembalikan (Aksi Utama) */
.container button[type="submit"] {
    width: 100%; /* Tombol lebar penuh */
    padding: 12px;
    background-color: #4CAF50; /* Hijau (biasa digunakan untuk aksi kembali/sukses) */
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.1s;
    margin-top: 15px;
}

/* Jika Anda ingin tombolnya ungu (opsi lain): */
/*
.container button[type="submit"] {
    background-color: #9C27B0; 
    color: white;
    ...
}
*/

.container button[type="submit"]:hover {
    background-color: #388E3C; /* Hijau lebih gelap saat hover */
    /* background-color: #7B1FA2; /* Ungu lebih gelap saat hover */
}

.container button[type="submit"]:active {
    transform: translateY(1px);
}

/* Link Kembali */
.container p a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #673AB7; /* Ungu untuk link kembali */
    text-decoration: none;
    font-weight: 500;
}

.container p a:hover {
    text-decoration: underline;
}
</style>
<body>
    <div class="container">
        <h2>Kembalikan: <?= htmlspecialchars($data['nama']) ?></h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Pengembali (atau nama peminjam)</label><br>
            <input type="text" name="peminjam" required><br>
            <label>Jumlah yang Dikembalikan</label><br>
            <input type="number" name="jumlah" value="1" min="1" required><br>
            <label>Catatan</label><br>
            <textarea name="catatan"></textarea><br><br>
            <button type="submit">Kembalikan</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>