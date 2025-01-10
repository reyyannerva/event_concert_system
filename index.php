<?php
session_start(); // Oturumu başlat // Veritabanı bağlantısı

// Kullanıcı giriş kontrolü
$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? null;

// Öne çıkan etkinlikleri almak için sıralı bir sorgu
$featured_events_query = "
    SELECT e.id AS event_id, e.name AS event_name, a.name AS artist_name, e.event_date, e.event_time, e.ticket_price
    FROM events e
    LEFT JOIN artists a ON e.artist_id = a.id
    WHERE e.name IN ('Tarkan Konseri', 'Sezen Aksu Konseri', 'Cem Yılmaz Gösterisi', 'Ajda Pekkan Konseri')
    ORDER BY FIELD(e.name, 'Tarkan Konseri', 'Sezen Aksu Konseri', 'Cem Yılmaz Gösterisi', 'Ajda Pekkan Konseri') ASC;
";
$featured_events_result = $conn->query($featured_events_query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Sistemi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #1c92d2, #f2fcfe);
            color: white;
            padding-top: 70px;
        }
        .navbar {
            background: linear-gradient(to right, #ff758c, #ff7eb3);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }
        .navbar .btn {
            margin-left: 10px;
            background: linear-gradient(to right, #1c92d2, #6dd5ed);
            color: white;
        }
        .navbar .btn:hover {
            background: linear-gradient(to right, #6dd5ed, #1c92d2);
        }
        .hero-section {
            text-align: center;
            padding: 40px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .hero-section p {
            font-size: 1.2rem;
        }
        .welcome-message {
            text-align: center;
            margin-top: 20px;
            font-size: 2.5rem;
            font-family: 'Dancing Script', cursive;
            font-weight: bold;
            color: #002366; /* Lacivert renk */
        }
        .slick-slider {
            margin: 20px auto;
        }
       .event-card {
            background: linear-gradient(to right, #ffffff, #f9f9f9);
            color: black;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }
        .event-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }
        .event-card:hover img {
            transform: scale(1.1);
        }
        .event-card h3 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333333;
        }
        .event-card p {
            margin: 5px 0;
            font-size: 1rem;
            color: #666666;
        }
        .event-card button {
            background: linear-gradient(to right, #ff758c, #ff7eb3);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-top: 10px;
        }
        .event-card button:hover {
            transform: scale(1.1);
            box-shadow: 0px 5px 20px rgba(255, 117, 140, 0.6);
        }
        .event-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 117, 140, 0.3), transparent 70%);
            z-index: 0;
            transition: opacity 0.5s;
            opacity: 0;
        }
        .event-card:hover::before {
            opacity: 1;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-bracsadzcxnd" href="index.php">🎟️ Konser & Etkinlik Sistemi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="all_events.php">Tüm Etkinlikler</a></li>
                <li class="nav-item"><a class="nav-link" href="map_view.php">Haritada Gör</a></li>
                <li class="nav-item"><a class="nav-link" href="calendar.php">Etkinlik Takvimi</a></li>
                <?php if ($is_logged_in): ?>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profilim</a></li>
                    <li class="nav-sdsdfdsfitem"><a class="btn btn-danger text-white" href="logout.php">Çıkış Yap</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-primary text-white" href="login.php">Giriş Yap</a></li>
                    <li class="nav-item"><a class="btn btn-success text-white" href="register.php">Kaydol</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="welcome-message">
        <?php if ($username): ?>
            Hoşgeldiniz, <?= htmlspecialchars($username) ?>! 🎉
        <?php else: ?>
            Hoşgeldiniz! 🎉
        <?php endif; ?>
    </div>

    <div class="hero-section">
        <h1>🎉 En Güzel Etkinlikler Seni Bekliyor</h1>
        <p>Etkinlikleri keşfet, biletlerini al ve eğlenceye başla!</p>
    </div>

    <h2 class="text-center mt-5">Öne Çıkan Etkinlikler</h2>
    <div class="slick-slider">
        <div class="event-card">
            <img src="images/tarkan_konser.jpg" alt="Tarkan Konseri">
            <h3>Tarkan Konseri</h3>
            <p>Sanatçı: Tarkan</p>
            <p>Tarih: 2025-01-05</p>
            <p>Saat: 20:00</p>
            <p>Fiyat: 750 TL</p>
            <button onclick="location.href='event_details.php?id=1'">Detay Gör</button>
        </div>
        <div class="event-card">
            <img src="images/sezenaksu.jpg" alt="Sezen Aksu Konseri">
            <h3>Sezen Aksu Konseri</h3>
            <p>Sanatçı: Sezen Aksu</p>
            <p>Tarih: 2025-01-06</p>
            <p>Saat: 21:00</p>
            <p>Fiyat: 800 TL</p>
            <button onclick="location.href='event_details.php?id=2'">Detay Gör</button>
        </div>
        <div class="event-card">
            <img src="images/cemyılmaz.jpg" alt="Cem Yılmaz Gösterisi">
            <h3>Cem Yılmaz Gösterisi</h3>
            <p>Sanatçı: Cem Yılmaz</p>
            <p>Tarih: 2025-01-07</p>
            <p>Saat: 19:30</p>
            <p>Fiyat: 500 TL</p>
            <button onclick="location.href='event_details.php?id=3'">Detay Gör</button>
        </div>
        <div class="event-card">
            <img src="images/ajdapekkan.jpg" alt="Ajda Pekkan Konseri">
            <h3>Ajda Pekkan Konseri</h3>
            <p>Sanatçı: Ajda Pekkan</p>
            <p>Tarih: 2025-01-08</p>
            <p>Saat: 20:30</p>
            <p>Fiyat: 700 TL</p>
            <button onclick="location.href='event_details.php?id=4'">Detay Gör</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function () {
        $('.slick-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
    });
</script>
</body>
</html>
