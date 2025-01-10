<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satın Alma Başarılı</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background: linear-gradient(to right, #56ccf2, #2f80ed, #ff7eb3);
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .success-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
        .success-container h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .success-container p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .event-info {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            margin-top: 10px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
        }
        .event-info h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .event-info p {
            margin: 5px 0;
        }
        .return-button {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            background: linear-gradient(to right, #ff7eb3, #ff758c);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .return-button:hover {
            transform: scale(1.1);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>🎉 Başarılı! 🎉</h1>
        <p>Bilet satın alma işleminiz başarıyla tamamlandı!</p>
        <div class="event-info">
            <h2>Etkinlik Bilgileri</h2>
            <p><strong>Etkinlik Adı:</strong> <?= htmlspecialchars($_GET['event_name'] ?? 'Bilinmiyor'); ?></p>
            <p><strong>Tarih:</strong> <?= htmlspecialchars($_GET['event_date'] ?? 'Bilinmiyor'); ?></p>
            <p><strong>Yer:</strong> <?= htmlspecialchars($_GET['venue_name'] ?? 'Bilinmiyor'); ?></p>
        </div>
        <a href="index.php" class="return-button">Ana Sayfaya Dön</a>
    </div>
</body>
</html>
