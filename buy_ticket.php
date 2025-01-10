<?php
session_start();
require 'config.php'; // Veritabanı bağlantısı

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    echo '
    <!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Giriş Yap</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
            body {
                font-family: "Poppins", sans-serif;
                background: linear-gradient(135deg, #ff758c, #ff7eb3);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            .login-reminder {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 15px;
                padding: 30px;
                text-align: center;
                box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            }
            .login-reminder h1 {
                font-size: 2rem;
                margin-bottom: 20px;
            }
            .login-reminder p {
                font-size: 1.2rem;
                margin-bottom: 30px;
            }
            .btn {
                padding: 10px 20px;
                font-size: 1rem;
                border-radius: 5px;
                margin: 10px;
                text-decoration: none;
                color: white;
            }
            .btn-primary {
                background: #56ccf2;
                border: none;
                transition: background 0.3s;
            }
            .btn-primary:hover {
                background: #2f80ed;
            }
            .btn-secondary {
                background: #ff7eb3;
                border: none;
                transition: background 0.3s;
            }
            .btn-secondary:hover {
                background: #ff5b99;
            }
        </style>
    </head>
    <body>
        <div class="login-reminder">
            <h1>⚠️ Lütfen Giriş Yapın</h1>
            <p>Bilet satın almak için giriş yapmanız gerekmektedir.</p>
            <a href="login.php" class="btn btn-primary">Giriş Yap</a>
            <a href="register.php" class="btn btn-secondary">Kayıt Ol</a>
        </div>
    </body>
    </html>
    ';
    exit;
}

$user_id = $_SESSION['user_id']; // Oturumdaki kullanıcı ID'si

// Etkinlik ID'sini kontrol et
$event_id = $_GET['event_id'] ?? null;
if (!$event_id || !is_numeric($event_id)) {
    die("Geçersiz etkinlik ID'si.");
}

// Etkinlik detaylarını al
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Etkinlik bulunamadı.");
}

// Kullanıcının favorilerde olup olmadığını kontrol et
$is_favorited = false;
$fav_stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND event_id = ?");
$fav_stmt->execute([$user_id, $event_id]);
$is_favorited = $fav_stmt->fetch(PDO::FETCH_ASSOC) ? true : false;

// Bilet satın alma işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_count = (int)$_POST['ticket_count'];
    if ($ticket_count < 1) {
        die("Geçersiz bilet sayısı.");
    }

    $total_price = $ticket_count * $event['ticket_price'];

    // Veritabanına bilet ekle
    $stmt = $conn->prepare("
        INSERT INTO tickets (user_id, event_id, ticket_count, total_price, purchase_date) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    try {
        $stmt->execute([$user_id, $event_id, $ticket_count, $total_price]);

        // Başarılı yönlendirme
        header("Location: success.php?event_name=" . urlencode($event['name']) .
               "&event_date=" . urlencode($event['event_date']) .
               "&venue_name=" . urlencode($event['venue_id']));
        exit();
    } catch (PDOException $e) {
        die("Hata: Bilet satın alınamadı. " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Satın Al</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e90ff, #ff69b4);
            color: #fff;
            padding: 30px 15px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .buy-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1, h2 {
            color: #fff;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 16px;
        }
        .buy-button {
            background: #56ccf2;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 18px;
        }
        .buy-button:hover {
            background: #2f80ed;
        }
        .favorite-button {
            background: #ff7eb3;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .favorite-button.favorited {
            background: #ff758c;
        }
    </style>
    <script>
        function toggleFavorite(button, eventId) {
            fetch(`add_favorites.php?event_id=${eventId}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.classList.toggle('favorited');
                        button.textContent = button.classList.contains('favorited') ? '❤️ Favorilerden Çıkar' : '❤️ Favorilere Ekle';
                    } else {
                        alert('Favorilere ekleme sırasında bir hata oluştu.');
                    }
                })
                .catch(error => console.error('Hata:', error));
        }
    </script>
</head>
<body>
    <div class="buy-container">
        <h1>🎫 Bilet Satın Al</h1>
        <h2>Etkinlik Bilgileri</h2>
        <p><strong>Etkinlik:</strong> <?= htmlspecialchars($event['name']) ?></p>
        <p><strong>Tarih:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
        <p><strong>Saat:</strong> <?= htmlspecialchars($event['event_time']) ?></p>
        <p><strong>Bilet Fiyatı:</strong> <?= htmlspecialchars($event['ticket_price']) ?> TL</p>

        <form method="POST">
            <label for="ticket_count">Bilet Sayısı:</label>
            <input type="number" name="ticket_count" id="ticket_count" min="1" placeholder="Bilet sayısını girin" required>
            <button type="submit" class="buy-button">🎟️ Satın Al</button>
        </form>

        <button class="favorite-button <?= $is_favorited ? 'favorited' : '' ?>" 
                onclick="toggleFavorite(this, <?= $event_id ?>)">
            <?= $is_favorited ? '❤️ Favorilerden Çıkar' : '❤️ Favorilere Ekle' ?>
        </button>
    </div>
</body>
</html>
