<?php
// login.php
require_once 'koneksi.php';
require_once 'helpers.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, fullname FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'] ?? $username;
            header('Location: index.php');
            exit;
        }
    }
    $error = "Username atau password salah.";
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Inventaris</title>
   <style>
    /* Styling untuk seluruh body */
body {
    font-family: Arial, sans-serif;
    background-color: #EDE7F6; /* Light Lavender/Ungu Muda untuk latar belakang */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

/* Styling untuk container (kotak login) */
.container {
    background-color: #FFFFFF; /* Latar belakang putih untuk kotak */
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Bayangan lembut */
    width: 300px;
    text-align: center;
}

/* Styling untuk judul H2 */
.container h2 {
    color: #6A1B9A; /* Ungu Gelap */
    margin-bottom: 25px;
    font-size: 24px;
}

/* Styling untuk label */
label {
    display: block;
    text-align: left;
    margin-top: 10px;
    margin-bottom: 5px;
    color: #4A148C; /* Ungu lebih gelap */
    font-weight: bold;
}

/* Styling untuk input text dan password */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 2px solid #D1C4E9; /* Border ungu muda */
    border-radius: 6px;
    box-sizing: border-box; /* Penting agar padding tidak menambah lebar */
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #6A1B9A; /* Ungu Gelap saat fokus */
    outline: none;
}

/* Styling untuk tombol Login */
button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #9C27B0; /* Ungu Terang/Magenta */
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.1s;
}

button[type="submit"]:hover {
    background-color: #7B1FA2; /* Ungu lebih gelap saat hover */
}

button[type="submit"]:active {
    transform: translateY(1px);
}

/* Styling untuk pesan error */
p.error {
    color: #FF1744; /* Merah untuk pesan error */
    background-color: #FFCDD2;
    border: 1px solid #FF8A80;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: left;
}
   </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Username</label><br>
            <input type="text" name="username" required><br>
            <label>Password</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>