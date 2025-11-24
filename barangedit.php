<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Barang tidak ditemukan.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    // adjust 'tersedia' bila total jumlah berubah
    $selisih = $jumlah - $data['jumlah'];
    $tersedia = $data['tersedia'] + $selisih;
    if ($tersedia < 0) $tersedia = 0;

    $stmt = $mysqli->prepare("UPDATE barang SET nama=?, deskripsi=?, jumlah=?, tersedia=?, lokasi=?, kode=? WHERE id=?");
    $stmt->bind_param('ssiissi', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode, $id);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Gagal update: " . $mysqli->error;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Barang</title>
</head>
<style>
    /* --- Global Form Container (Reused from previous forms) --- */
.container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #FFFFFF;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    /* Hapus text-align: center agar label sejajar kiri */
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
    min-height: 100px; /* Sedikit lebih tinggi untuk deskripsi */
}

/* Fokus State */
.container input[type="text"]:focus,
.container input[type="number"]:focus,
.container textarea:focus {
    border-color: #6A1B9A; /* Ungu Gelap saat fokus */
    outline: none;
}

/* Tombol Update */
.container button[type="submit"] {
    width: 100%; /* Tombol lebar penuh */
    padding: 12px;
    background-color: #9C27B0; /* Ungu Terang/Magenta */
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.1s;
    margin-top: 15px; /* Jarak dari input terakhir */
}

.container button[type="submit"]:hover {
    background-color: #7B1FA2; /* Ungu lebih gelap saat hover */
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
        <h2>Edit Barang</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama</label><br>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br>
            <label>Kode</label><br>
            <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>"><br>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea><br>
            <label>Jumlah (total)</label><br>
            <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="0" required><br>
            <label>Lokasi</label><br>
            <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>"><br><br>
            <button type="submit">Update</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>