<?php
// koneksi.php
$host = "localhost";
$user = "root"; 
$pass = "";     
$db   = "jihann"; 

// Menggunakan $mysqli sebagai nama variabel koneksi
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>