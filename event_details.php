<?php
require_once 'config.php'; // Veritabanı bağlantısı
session_start();

// Etkinlik ID'sini al
$event_id = $_GET['id'] ?? null;
if (!$event_id || !is_numeric($event_id)) {
    die("Geçersiz etkinlik ID'si!");
}

// Etkinlik detaylarını sorgula
$sql = "SELECT 
            e.name AS event_name,
            e.event_date AS date,
            e.event_time AS time,
            e.ticket_price AS price,
            et.type_name AS type,
            et.emoji AS emoji,
            v.name AS venue_name,
            c.name AS city_name,
            a.name AS artist_name,
            v.capacity AS venue_capacity
        FROM events e
        LEFT JOIN event_types et ON e.event_type_id = et.id
        LEFT JOIN venues v ON e.venue_id = v.id
        LEFT JOIN cities c ON v.city_id = c.id
        LEFT JOIN artists a ON e.artist_id = a.id
        WHERE e.id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Etkinlik bulunamadı!");
}

// Kullanıcının favorilerde olup olmadığını kontrol et
$is_favorited = false;
if (isset($_SESSION['user_id'])) {
    $sql_check_favorite = "SELECT * FROM favorites WHERE user_id = ? AND event_id = ?";
    $stmt_check_favorite = $conn->prepare($sql_check_favorite);
    $stmt_check_favorite->execute([$_SESSION['user_id'], $event_id]);
    $is_favorited = $stmt_check_favorite->fetch(PDO::FETCH_ASSOC) ? true : false;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['event_name']) ?> - Etkinlik Detayları</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e90ff, #ff69b4);
            color: #fff;
            padding-top: 70px;
        }
        .event-details-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            margin-top: 50px;
        }
        .event-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 30px;
            color: #fff;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .details-table th {
            background: rgba(255, 255, 255, 0.3);
            font-weight: bold;
            text-align: left;
        }
        .buy-ticket-btn {
            background: #56ccf2;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 25px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .buy-ticket-btn:hover {
            background: #2f80ed;
        }
        .favorite-button {
            background: #ff7eb3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin-left: 10px;
        }
        .favorite-button.favorited {
            background: #ff758c;
        }
        .social-share {
            margin-top: 20px;
            text-align: center;
        }
        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .social-btn i {
            margin-right: 10px;
            font-size: 20px;
        }
        .social-btn.facebook {
            background: #4267B2;
        }
        .social-btn.twitter {
            background: #1DA1F2;
        }
        .social-btn.linkedin {
            background: #2867B2;
        }
        .social-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script>
        function toggleFavorite(button, eventId) {
            fetch(`add_to_favorites.php?event_id=${eventId}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.classList.toggle('favorited');
                        button.textContent = button.classList.contains('favorited') 
                            ? '❤️ Favorilerden Çıkar' 
                            : '🤍 Favorilere Ekle';
                    } else {
                        alert('Favorilere ekleme sırasında bir hata oluştu.');
                    }
                })
                .catch(error => console.error('Hata:', error));
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">🎟️ Bubilet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Ana Sayfa</a></li>
                <li class="nav-item"><a class="nav-link" href="all_events.php">Tüm Etkinlikler</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profilim</a></li>
                <li class="nav-item"><a class="btn btn-primary text-white" href="login.php">Giriş Yap</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container event-details-container">
    <div class="event-header">
        <?= htmlspecialchars($event['emoji']) ?> <?= htmlspecialchars($event['event_name']) ?>
    </div>
    <table class="details-table">
        <tr>
            <th>Kategori</th>
            <td><?= htmlspecialchars($event['type']) ?></td>
        </tr>
        <tr>
            <th>Tarih</th>
            <td><?= htmlspecialchars($event['date']) ?></td>
        </tr>
        <tr>
            <th>Saat</th>
            <td><?= htmlspecialchars($event['time']) ?></td>
        </tr>
        <tr>
            <th>Mekan</th>
            <td><?= htmlspecialchars($event['venue_name']) ?> (Kapasite: <?= htmlspecialchars($event['venue_capacity']) ?>)</td>
        </tr>
        <tr>
            <th>Şehir</th>
            <td><?= htmlspecialchars($event['city_name']) ?></td>
        </tr>
        <tr>
            <th>Sanatçı</th>
            <td><?= htmlspecialchars($event['artist_name'] ?? 'Belirtilmedi') ?></td>
        </tr>
        <tr>
            <th>Bilet Fiyatı</th>
            <td><?= htmlspecialchars($event['price']) ?> TL</td>
        </tr>
    </table>
    <div class="text-center">
        <button class="buy-ticket-btn" onclick="location.href='buy_ticket.php?event_id=<?= $event_id ?>'">🎟️ Bilet Satın Al</button>
        <?php if (isset($_SESSION['user_id'])): ?>
            <button 
                class="favorite-button <?= $is_favorited ? 'favorited' : '' ?>" 
                onclick="toggleFavorite(this, <?= $event_id ?>)">
                <?= $is_favorited ? '❤️ Favorilerden Çıkar' : '🤍 Favorilere Ekle' ?>
            </button>
        <?php endif; ?>
    </div>
    <div class="social-share">
        <p>Bu etkinliği paylaş:</p>
        <a href="https://facebook.com/sharer/sharer.php?u=<?= urlencode('http://example.com/event_details.php?event_id=' . $event_id) ?>" 
           target="_blank" class="social-btn facebook">
            <i class="fab fa-facebook-f"></i> Facebook
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://example.com/event_details.php?event_id=' . $event_id) ?>" 
           target="_blank" class="social-btn twitter">
            <i class="fab fa-twitter"></i> Twitter
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode('http://example.com/event_details.php?event_id=' . $event_id) ?>" 
           target="_blank" class="social-btn linkedin">
            <i class="fab fa-linkedin-in"></i> LinkedIn
        </a>
    </div>
</div>
</body>
</html>
