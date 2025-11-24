<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    if ($nama === '' || $jumlah < 0) $error = 'Nama dan jumlah harus diisi dengan benar.';
    else {
        $tersedia = $jumlah;
        $stmt = $mysqli->prepare("INSERT INTO barang (nama, deskripsi, jumlah, tersedia, lokasi, kode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiiss', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode);
        if ($stmt->execute()) {
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
    <title>Tambah Barang</title>
    <style>
        /* --- Global Form Styling (umumnya bisa digabung dengan login/edit form) --- */

/* Styling untuk container (jika berbeda dari container utama) */
/* Biasanya, .container bisa dipakai ulang dari style login */
/* .container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #FFFFFF;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
} */ /* Uncomment dan sesuaikan jika .container di sini perlu spesifik */


.container h2 {
    color: #6A1B9A; /* Ungu Gelap untuk judul */
    margin-bottom: 25px;
    font-size: 24px;
    text-align: center; /* Pastikan judul di tengah */
}

/* Styling untuk pesan error (sama seperti login) */
p.error {
    color: #FF1744; /* Merah untuk pesan error */
    background-color: #FFCDD2;
    border: 1px solid #FF8A80;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: left;
}

/* Styling untuk label */
.container label {
    display: block;
    text-align: left;
    margin-top: 10px;
    margin-bottom: 5px;
    color: #4A148C; /* Ungu lebih gelap */
    font-weight: bold;
}

/* Styling untuk input text, number, dan textarea */
.container input[type="text"],
.container input[type="number"],
.container textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 2px solid #D1C4E9; /* Border ungu muda */
    border-radius: 6px;
    box-sizing: border-box; /* Penting agar padding tidak menambah lebar */
    transition: border-color 0.3s;
    font-size: 14px; /* Ukuran font yang konsisten */
    resize: vertical; /* Memungkinkan textarea diubah ukurannya secara vertikal */
    min-height: 80px; /* Tinggi minimum untuk textarea */
}

.container input[type="text"]:focus,
.container input[type="number"]:focus,
.container textarea:focus {
    border-color: #6A1B9A; /* Ungu Gelap saat fokus */
    outline: none;
}

/* Styling untuk tombol */
.container button[type="submit"] {
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.1s;
    margin-right: 10px; /* Jarak antar tombol */
}

/* Tombol Simpan */
.container button[type="submit"]:first-of-type { /* Tombol pertama adalah "Simpan" */
    background-color: #9C27B0; /* Ungu Terang/Magenta */
    color: white;
}

.container button[type="submit"]:first-of-type:hover {
    background-color: #7B1FA2; /* Ungu lebih gelap saat hover */
}

/* Tombol Kembali (jika ada lebih dari satu tombol submit) */
.container button[type="submit"]:last-of-type { /* Tombol kedua adalah "Kembali" */
    background-color: #E0E0E0; /* Abu-abu terang */
    color: #424242; /* Abu-abu gelap */
}

.container button[type="submit"]:last-of-type:hover {
    background-color: #BDBDBD; /* Abu-abu lebih gelap saat hover */
}

.container button[type="submit"]:active {
    transform: translateY(1px);
}
    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Barang</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Barang</label><br>
            <input type="text" name="nama" required><br>
            <label>Kode (unik)</label><br>
            <input type="text" name="kode"><br>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"></textarea><br>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" value="1" min="0" required><br>
            <label>Lokasi</label><br>
            <input type="text" name="lokasi"><br><br>
            <button type="submit">Simpan</button>
            <button type="submit" onclick="window.location.href='index.php'">Kembali</button>
        </form>

    </div>
</body>

</html>