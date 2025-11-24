<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$sql = "SELECT t.*, b.nama AS nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id ORDER BY t.tanggal DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transaksi</title>
</head>
<style>
    /* --- Global & Container Styling --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #EDE7F6; /* Latar belakang ungu muda */
    margin: 20px;
}

.container {
    max-width: 1100px;
    margin: 0 auto;
    background-color: #FFFFFF;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Judul H2 */
.container h2 {
    color: #5E35B1; /* Ungu sedang untuk judul */
    margin-bottom: 20px;
    font-size: 26px;
    text-align: center;
}

/* Link Kembali */
.container p a {
    color: #8E24AA; /* Ungu terang untuk link */
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 15px;
    display: inline-block; /* Agar margin bawah berfungsi */
    transition: color 0.3s;
}

.container p a:hover {
    color: #4A148C; /* Ungu gelap saat hover */
    text-decoration: underline;
}

/* --- Tabel Styling --- */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background-color: #FFFFFF;
}

/* Header Tabel */
table th {
    background-color: #7E57C2; /* Ungu yang lebih dalam untuk header */
    color: white;
    padding: 12px;
    text-align: left;
    border: 1px solid #7E57C2;
    font-size: 14px;
    text-transform: uppercase;
}

/* Baris Data */
table td {
    padding: 10px;
    border: 1px solid #D1C4E9; /* Border ungu muda */
    font-size: 14px;
    vertical-align: top;
}

/* Warna berdasarkan Jenis Transaksi (Opsional: untuk visualisasi cepat) */
table tr td:nth-child(4) { /* Kolom Jenis Transaksi (kolom ke-4) */
    font-weight: bold;
}

table tr td:nth-child(4):contains("pinjam") {
    color: #1E88E5; /* Biru untuk Pinjam */
}

table tr td:nth-child(4):contains("kembali") {
    color: #4CAF50; /* Hijau untuk Kembali */
}

/* Hover pada baris data untuk highlight */
table tr:nth-child(even) {
    background-color: #F3E5F5; /* Baris genap sedikit lebih gelap (lavender) */
}

table tr:hover {
    background-color: #E1BEE7; /* Highlight ungu saat mouse di atas baris */
}
</style>
<body>
    <div class="container">
        <h2>Daftar Transaksi</h2>
        <p><a href="index.php">Kembali</a></p>
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Tanggal</th>
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>

                    <td><?= $row['tanggal'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['peminjam']) ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= htmlspecialchars($row['catatan']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>