<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çıkış Yapıldı</title>
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
        .logout-container {
            background: white;
            color: black;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            width: 400px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .logout-container h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #1c92d2;
        }
        .logout-container p {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #555;
        }
        .home-button {
            padding: 10px 20px;
            background: linear-gradient(to right, #1c92d2, #6dd5ed);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }
        .home-button:hover {
            background: linear-gradient(to right, #6dd5ed, #1c92d2);
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Oturumunuz Kapatıldı</h1>
        <p>Başarıyla çıkış yaptınız. Ana sayfaya dönebilirsiniz.</p>
        <a href="index.php" class="home-button">Ana Sayfaya Dön</a>
    </div>
</body>
</html>
