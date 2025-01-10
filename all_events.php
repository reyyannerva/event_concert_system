<?php
require_once 'config.php'; // Veritabanı bağlantısı

// Filtreleme için değişkenler
$event_type = $_GET['category'] ?? ''; 
$city = $_GET['city'] ?? '';
$date = $_GET['date'] ?? '';
$venue = $_GET['venue'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$search = $_GET['search'] ?? ''; // Arama kutusu için ekleme

// SQL sorgusu başlangıcı
$sql = "SELECT 
            e.id AS event_id,
            e.name AS event_name,
            et.type_name AS type,
            et.emoji AS emoji,
            v.name AS venue_name,
            c.name AS city_name,
            a.name AS artist_name,
            e.event_date AS date,
            e.event_time AS time,
            e.ticket_price
        FROM events e
        LEFT JOIN event_types et ON e.event_type_id = et.id
        LEFT JOIN venues v ON e.venue_id = v.id
        LEFT JOIN cities c ON v.city_id = c.id
        LEFT JOIN artists a ON e.artist_id = a.id
        WHERE 1=1";

// Parametreler için array
$params = [];

// Görsellerin eşleştirilmesi
$images = [
    'Tarkan Konseri' => 'tarkan_konser.jpg',
    'Sezen Aksu Konseri' => 'sezenaksu.jpg',
    'Cem Yılmaz Gösterisi' => 'cemyılmaz.jpg',
    'Ajda Pekkan Konseri' => 'ajdapekkan.jpg',
    'Teoman Konseri' => 'teoman_konser.jpg',
    'Şebnem Ferah Konseri' => 'sebnem_ferah_konser.jpg',
    'Haluk Levent Konseri' => 'haluk_levent_konser.jpg',
    'Mor ve Ötesi Konseri' => 'mor_ve_otesi_konser.jpg',
    'Duman Konseri' => 'duman_konser.jpg',
    'Edis Konseri' => 'edis_konser.jpg',
    'Tiyatro: Hamlet' => 'hamlet.jpg',
    'Sinema Gösterimi: İnception' => 'inception.jpg',
    'Stand-up: Ata Demirer' => 'cemyılmaz.jpg',
    'Dans Gösterisi: Anadolu Ateşi' => 'anadolu_atesi_dans.jpg',
    'Opera: La Traviata' => 'opera_la_traviata.jpg',
    'Sempozyum: Yapay Zeka ve Gelecek' => 'yapayzeka_sempozyum.jpg',
    'Festival: Ege Müzik Festivali' => 'ege_muzik_festivali.jpg',
    'Workshop: Seramik Sanatı' => 'seramik_workshop.jpg',
    'Çocuk Tiyatrosu: Pinokyo' => 'pinokyo_tiyatro.jpg',
    'Sergi Açılışı: Modern Sanat' => 'sergi_acilisi.jpg',
    'Belgesel Gösterimi: Gezegenimiz' => 'belgesel_gezegenimiz.jpg',
    'Klasik Müzik Konseri: Mozart Gecesi' => 'mozart.jpg',
    'Caz Konseri: İlhan Erşahin' => 'caz_ilhan_ersahin.jpg',
    'Elektronik Müzik Partisi: DJ Armin Van Buuren' => 'elektronik_muzik.jpg',
    'Rock Konseri: Duman' => 'rock_duman_konseri.jpg',
    'Pop Konseri: Kenan Doğulu' => 'kenan_dogulu_konser.jpg',
    'Sinema Gösterimi: Avatar 2' => 'avatar.jpg',
    'Workshop: Ebru Sanatı' => 'ebru_workshop.jpg',
    'Tiyatro: Othello' => 'othello_tiyatro.jpg',
    'Festival: Karadeniz Yayla Festivali' => 'karadeniz_yayla_festivali.jpg',
    'Stand-up: Cem Yılmaz' => 'cemyılmaz.jpg',
    'Sergi Açılışı: Osmanlı Hat Sanatı' => 'osmanlı_hat_sanatı.jpg',
    'Elektronik Müzik Partisi: DJ Tiesto' => 'elektronik_muzik_djtiasto.jpg',
    'Klasik Müzik Konseri: Beethoven Gecesi' => 'beethoven.jpg',
    'Caz Konseri: Kerem Görsev Trio' => 'kerem_görsev_caz.jpg',
    'Pop Konseri: Edis' => 'pop_edis.jpg',
    'Belgesel Gösterimi: Doğanın Mucizeleri' => 'doga_mucize_belgesel.jpg',
    'Çocuk Tiyatrosu: Alaaddin ve Sihirli Lamba' => 'alaaddin.jpg',
];


// Filtreleme sorgularını ekleyelim
if ($event_type) {
    $sql .= " AND e.event_type_id = ?";
    $params[] = $event_type;
}
if ($city) {
    $sql .= " AND c.name LIKE ?";
    $params[] = "%$city%";
}
if ($date) {
    $sql .= " AND e.event_date = ?";
    $params[] = $date;
}
if ($venue) {
    $sql .= " AND e.venue_id = ?";
    $params[] = $venue;
}
if ($price_min && $price_max) {
    $sql .= " AND e.ticket_price BETWEEN ? AND ?";
    $params[] = $price_min;
    $params[] = $price_max;
}
if ($search) {
    $sql .= " AND e.name LIKE ?";
    $params[] = "%$search%";
}

// Etkinlikleri sorgulama
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Etkinlikler</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>


    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e90ff, #ff69b4);
            color: #fff;
            padding-top: 70px;
        }
        .filter-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .filter-input {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .filter-button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background: linear-gradient(to right, #56ccf2, #2f80ed);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .filter-button:hover {
            transform: scale(1.05);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
        .event-card img {
    width: 100%; /* Görselin genişliği kutuyu doldurur */
    height: 250px; /* Kutunun yüksekliği */
    object-fit: contain; /* Görselin kırpılmadan kutuya sığması */
    background: linear-gradient(135deg, #1e90ff, #ff69b4); /* Arka plan degrade */
    border-radius: 10px; /* Kenarların yuvarlaklaşması */
    padding: 10px; /* Görselin çevresine boşluk eklenir */
    margin-bottom: 10px; /* Görsel ile metin arasında boşluk */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Hover efekti */
}
.event-card img:hover {
    transform: scale(1.05); /* Hover efektiyle görsel büyütme */
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3); /* Hover sırasında gölge */
}

.event-card {
    background: rgba(255, 255, 255, 0.1); /* Hafif şeffaflık */
    border-radius: 10px; /* Yuvarlak köşeler */
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); /* Hafif gölge efekti */
    padding: 15px; /* İçerik için boşluk */
    margin-bottom: 20px; /* Kartlar arasında boşluk */
    text-align: center; /* İçeriği ortalar */
    transition: transform 0.3s, box-shadow 0.3s; /* Hover efekti */
}
.event-card:hover {
    transform: translateY(-10px); /* Hover efektiyle yukarı hareket */
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.3); /* Daha belirgin gölge */
}


        .favorite-button:hover {
            background: linear-gradient(135deg, #ff4081, #ff7eb3);
            box-shadow: 0px 5px 15px rgba(255, 64, 129, 0.3);
        }
        .details-button {
            font-size: 16px;
            color: white;
            background: linear-gradient(135deg, #56ccf2, #2f80ed);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .details-button:hover {
            background: linear-gradient(135deg, #2f80ed, #56ccf2);
            box-shadow: 0px 5px 15px rgba(87, 204, 242, 0.3);
        }
    </style>
    <script>
        function toggleFavorite(button, eventId) {
            fetch(`add_favorites.php?event_id=${eventId}`, { method: 'POST' })
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

        function showSuggestions(input, type) {
            const term = input.value;
            const suggestionsBox = document.getElementById(`${input.id}-suggestions`);

            if (term.length < 2) {
                suggestionsBox.innerHTML = '';
                return;
            }

            fetch(`get_suggestions.php?type=${type}&term=${term}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.textContent = item;
                        div.onclick = () => {
                            input.value = item;
                            suggestionsBox.innerHTML = '';
                        };
                        suggestionsBox.appendChild(div);
                    });
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

<div class="container mt-5">
    <h1 class="text-center mb-4">📅 Tüm Etkinlikler</h1>

    <!-- Filtreleme Formu -->
    <div class="filter-container">
        <form id="filter-form" method="GET" action="all_events.php">
            <input 
                type="text" 
                name="search" 
                class="filter-input" 
                placeholder="Etkinlik ara..." 
                value="<?= htmlspecialchars($search) ?>"
            >
            <input 
                type="date" 
                name="date" 
                class="filter-input" 
                value="<?= htmlspecialchars($date) ?>"
            >
            <select name="category" class="filter-input">
                <option value="">Etkinlik Türü Seçin</option>
                <option value="Konser" <?= $event_type == 'Konser' ? 'selected' : '' ?>>Konser</option>
                <option value="Tiyatro" <?= $event_type == 'Tiyatro' ? 'selected' : '' ?>>Tiyatro</option>
                <option value="Sinema" <?= $event_type == 'Sinema' ? 'selected' : '' ?>>Sinema</option>
            </select>
            <input 
                type="text" 
                name="city" 
                class="filter-input" 
                placeholder="Şehir" 
                value="<?= htmlspecialchars($city) ?>"
            >
            <button type="submit" class="filter-button">Filtrele</button>
        </form>
    </div>

    <!-- Etkinlik Kartları -->
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4">
                <div class="event-card">
                    <?php 
                        $image_name = $images[$event['event_name']] ?? 'default.jpg';
                        $image_path = "images/" . $image_name;
                    ?>
                    <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($event['event_name']) ?>">
                    <h5><?= htmlspecialchars($event['event_name']) ?></h5>
                    <p><strong>Tarih:</strong> <?= htmlspecialchars($event['date']) ?></p>
                    <p><strong>Şehir:</strong> <?= htmlspecialchars($event['city_name']) ?></p>
                    <p><strong>Bilet Fiyatı:</strong> <?= htmlspecialchars($event['ticket_price']) ?> TL</p>
                    <button 
                        class="favorite-button" 
                        onclick="toggleFavorite(this, <?= $event['event_id'] ?>)">
                        🤍 Favorilere Ekle
                    </button>
                    <button 
                        class="details-button" 
                        onclick="location.href='event_details.php?id=<?= $event['event_id'] ?>'">
                        🔍 Detay Gör
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="mt-5 text-center">📅 Takvim Görünümü</h2>
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                <?php foreach ($events as $row): ?>
                {
                    title: '<?= $row['event_name'] ?>',
                    start: '<?= $row['date'] ?>T<?= $row['time'] ?>'
                },
                <?php endforeach; ?>
            ]
        });
        calendar.render();
    });
</script>
</body>
</html>
