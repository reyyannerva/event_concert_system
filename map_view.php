<?php
session_start(); // Oturumu başlat
require 'config.php'; // Veritabanı bağlantısı

// Veritabanı bağlantı kontrolü
if (!isset($conn)) {
    die("Veritabanı bağlantısı kurulamadı. Lütfen config.php dosyasını kontrol edin.");
}

// Şehirlerdeki etkinlikleri al
$stmt = $conn->prepare("SELECT 
    c.name AS city_name,
    c.latitude, 
    c.longitude, 
    COUNT(e.id) AS event_count,
    GROUP_CONCAT(e.name SEPARATOR ', ') AS event_names
FROM events e
JOIN venues v ON e.venue_id = v.id
JOIN cities c ON v.city_id = c.id
WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL
GROUP BY c.id");
$stmt->execute();
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haritada Etkinlikleri Gör</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/leaflet.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #1c92d2, #f2fcfe);
        }
        #map {
            width: 100%;
            height: 600px;
            margin: 20px auto;
            border: 2px solid #1c92d2;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #1c92d2;
            padding: 10px;
        }
        .leaflet-popup-content-wrapper {
            background: linear-gradient(to right, #ff7eb3, #ff758c);
            color: white;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.5);
            padding: 10px;
            max-width: 120px;
            min-width: 120px;
        }
        .leaflet-popup-content img {
            display: block;
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
        .leaflet-popup-content .event-name {
            font-size: 12px;
            text-align: center;
            margin-top: 5px;
            color: #fff;
        }
        .leaflet-popup-content a {
            display: block;
            margin-top: 5px;
            padding: 5px;
            background-color: #ffd700;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            font-size: 12px;
        }
        .leaflet-popup-content a:hover {
            background-color: #ffc107;
        }
    </style>
</head>
<body>
    <h1>Haritada Etkinlik Yerleri</h1>
    <div id="map"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/leaflet.js"></script>
    <script>
        // Haritayı başlat
        var map = L.map('map').setView([39.9208, 32.8541], 6); // Türkiye koordinatları

        // OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // PHP'den etkinlik verilerini çek
        var cities = <?php echo json_encode($cities); ?>;
        var images = <?php echo json_encode($images); ?>;

        // Marker ekle
        cities.forEach(function(city) {
            if (city.latitude && city.longitude) {
                var eventNames = city.event_names.split(', ');
                var imageGallery = eventNames.map(name => {
                    var image = images[name] || 'default.jpg';
                    return `
                        <img src="images/${image}" alt="Etkinlik Görseli">
                        <div class="event-name">${name}</div>
                    `;
                }).join('');

                var popupContent = `
                    <div>${imageGallery}</div>
                    <a href='all_events.php?city=${encodeURIComponent(city.city_name)}'>Detay Gör</a>
                `;

                var marker = L.marker([parseFloat(city.latitude), parseFloat(city.longitude)]).addTo(map);
                marker.bindPopup(popupContent);
            }
        });
    </script>
</body>
</html>
