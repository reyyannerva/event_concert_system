<?php
include 'config.php'; // Veritabanı bağlantısı

try {
    // SQL sorgusu: Etkinlik bilgilerini tüm ilişkili tablolardan al
    $query = "
        SELECT 
            e.name AS event_name,
            et.type AS type,
            v.name AS venue_name,
            c.name AS city,
            a.name AS artist_name,
            e.date,
            e.time,
            e.ticket_price
        FROM events e
        LEFT JOIN event_types et ON e.type_id = et.id
        LEFT JOIN venues v ON e.venue_id = v.id
        LEFT JOIN cities c ON v.city_id = c.id
        LEFT JOIN artists a ON e.artist_id = a.id
        ORDER BY e.date ASC
    ";

    // Sorguyu hazırlayıp çalıştırma
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı sorgu hatası: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Etkinlikler</title>
    <link rel="stylesheet" href="asset_styles.css"> <!-- CSS Dosyası -->
</head>
<body>
    <header>
        <h1>📅 Tüm Etkinlikler</h1>
    </header>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Etkinlik Adı</th>
                    <th>Tür</th>
                    <th>Şehir</th>
                    <th>Mekan</th>
                    <th>Sanatçı</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Bilet Fiyatı</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verileri döngüyle çekme ve tablodaki hücrelere yerleştirme
                foreach ($result as $row) {
                    // Tüm alanları kontrol ederek eksik olanlara varsayılan değerler atıyoruz
                    $event_name = $row['event_name'] ?? 'Bilinmiyor';
                    $type = $row['type'] ?? 'Belirtilmemiş';
                    $city = $row['city'] ?? 'Bilinmiyor';
                    $venue = $row['venue_name'] ?? 'Belirtilmemiş';
                    $artist = $row['artist_name'] ?? 'Bilinmiyor';
                    $date = $row['date'] ?? 'Tarih Yok';
                    $time = $row['time'] ?? 'Saat Yok';
                    $price = isset($row['ticket_price']) ? number_format($row['ticket_price'], 2) : 'Fiyat Yok';

                    echo "
                        <tr>
                            <td>{$event_name}</td>
                            <td>{$type}</td>
                            <td>{$city}</td>
                            <td>{$venue}</td>
                            <td>{$artist}</td>
                            <td>" . date('d-m-Y', strtotime($date)) . "</td>
                            <td>{$time}</td>
                            <td>{$price} TL</td>
                        </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

