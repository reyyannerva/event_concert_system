<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Kullanıcı adı boşlukları kırpılır
    $password = $_POST['password']; // Şifre alınır

    try {
        // Kullanıcıyı veritabanından al
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kullanıcı ve şifre kontrolü
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Kullanıcı adı oturumda saklanır
            header("Location: index.php");
            exit;
        } else {
            $error = "Kullanıcı adı veya şifre hatalı.";
        }
    } catch (PDOException $e) {
        $error = "Veritabanı hatası: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oturum Aç</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #1c92d2, #f2fcfe);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .auth-container {
            background: white;
            color: black;
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .auth-container h1 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
            color: #1c92d2;
        }
        .auth-container label {
            font-size: 1rem;
            margin-top: 10px;
            color: #555;
        }
        .auth-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .auth-button {
            width: 100%;
            padding: 10px;
            background: linear-gradient(to right, #1c92d2, #6dd5ed);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .auth-button:hover {
            background: linear-gradient(to right, #6dd5ed, #1c92d2);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>🔑 Oturum Aç</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Şifre:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="auth-button">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
