<?php
require 'config.php'; // Veritabanı bağlantısı

// PDO bağlantısının tanımlı olup olmadığını kontrol edin
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
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Haritası</title>
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
    </style>
</head>
<body>
    <h1>Etkinlik Haritası</h1>
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
        var events = <?php echo json_encode($events); ?>;

        // Marker ekle
        events.forEach(function(event) {
            if (event.latitude && event.longitude) {
                var marker = L.marker([event.latitude, event.longitude]).addTo(map);
                marker.bindPopup(
                    `<strong>${event.city_name}</strong><br>` +
                    `Etkinlik Sayısı: ${event.event_count}<br>` +
                    `Etkinlikler: ${event.event_names}<br>` +
                    `<a href='all_events.php?city=${encodeURIComponent(event.city_name)}'>Detay Gör</a>`
                );
            }
        });
    </script>
</body>
</html>
