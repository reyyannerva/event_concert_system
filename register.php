<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Kullanıcı adı boşlukları kırpılır
    $email = trim($_POST['email']); // E-posta boşlukları kırpılır
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Şifre hashlenir

    try {
        // Kullanıcı bilgilerini veritabanına ekler
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            // Başarılı kayıt sonrası giriş sayfasına yönlendirme
            header("Location: login.php?message=registered");
            exit;
        } else {
            $error = "Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.";
        }
    } catch (PDOException $e) {
        // Benzersiz kısıtlama hatası kontrolü (ör. benzersiz e-posta veya kullanıcı adı)
        if ($e->getCode() === '23000') {
            $error = "Bu kullanıcı adı veya e-posta zaten kayıtlı.";
        } else {
            $error = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            color: #ff6b6b;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .auth-button {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background 0.3s ease;
        }
        .auth-button:hover {
            background: #2575fc;
        }
        .auth-footer {
            text-align: center;
            margin-top: 20px;
        }
        .auth-footer a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>📝 Kaydol</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" name="username" id="username" required>
            <label for="email">E-posta:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Şifre:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="auth-button">Kaydol</button>
        </form>
        <div class="auth-footer">
            <p>Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
        </div>
    </div>
</body>
</html>
