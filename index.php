<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

// ambil list barang
$result = $mysqli->query("SELECT * FROM barang ORDER BY created_at DESC");
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventaris Barang</title>
    <style>
        /* --- Global Styling --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #EDE7F6; /* Latar belakang ungu muda */
    margin: 20px;
}

/* --- Container dan Header --- */
.container {
    max-width: 1000px;
    margin: 0 auto;
    background-color: #FFFFFF;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.container h1 {
    color: #5E35B1; /* Ungu sedang untuk judul */
    margin-bottom: 5px;
}

.container p {
    color: #4A148C; /* Ungu gelap untuk teks */
}

/* --- Link Navigasi --- */
.container p a {
    color: #8E24AA; /* Ungu terang untuk link */
    text-decoration: none;
    font-weight: 600;
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
    margin-top: 20px;
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

/* Hover pada baris data untuk highlight */
table tr:nth-child(even) {
    background-color: #F3E5F5; /* Baris genap sedikit lebih gelap (lavender) */
}

table tr:hover {
    background-color: #E1BEE7; /* Highlight ungu saat mouse di atas baris */
}

/* --- Styling Kolom Aksi --- */
table td:last-child {
    white-space: nowrap; /* Mencegah link aksi terpotong */
    text-align: center;
}

/* Link Aksi (Edit, Hapus, Pinjam, Kembalikan) */
table td a {
    color: #673AB7; /* Ungu untuk link aksi */
    text-decoration: none;
    margin: 0 4px;
}

table td a:hover {
    color: #4527A0; /* Ungu gelap saat hover */
    text-decoration: underline;
}

/* Styling Khusus untuk Link Hapus */
table td a[href*="barangdltd.php"] {
    color: #E53935; /* Merah untuk aksi Hapus (keamanan) */
}

/* Styling Khusus untuk Link Pinjam */
table td a[href*="pinjam.php"] {
    color: #1E88E5; /* Biru untuk aksi Pinjam */
}

/* Styling untuk teks "Kosong" */
table td span {
    color: gray;
    font-style: italic;
}
    </style>
</head>

<body>
    <div class="container">
        <h1>Inventaris Barang</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['user_name']) ?> <br>

        </p>
        <p>
            <a href="barangadd.php">Tambah Barang</a> |
            <a href="transaksi.php">Lihat Transaksi</a> | <a href="logout.php">Logout</a>
        </p>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Kode</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Tersedia</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>

                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tersedia'] ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td>
                        <a href="barangedit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="barangdltd.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus barang?')">Hapus</a> |
                        <?php if ($row['tersedia'] > 0): ?>
                            <a href="pinjam.php?id=<?= $row['id'] ?>">Pinjam</a>
                        <?php else: ?>
                            <span style="color:gray">Kosong</span>
                        <?php endif; ?>
                        <?php if ($row['jumlah'] > $row['tersedia']): ?>
                            | <a href="kembalikan.php?id=<?= $row['id'] ?>">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>