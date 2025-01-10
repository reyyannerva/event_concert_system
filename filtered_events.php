<?php
require 'config.php'; // Veritabanı bağlantısı

// Filtreleme parametrelerini al
$event_type = $_GET['event_type'] ?? '';
$city = $_GET['city'] ?? '';
$date = $_GET['date'] ?? '';
$search = $_GET['search'] ?? '';

// SQL sorgusu başlangıcı
$sql = "
    SELECT 
        e.id AS event_id,
        e.name AS event_name,
        et.type_name AS type,
        et.emoji AS emoji,
        v.name AS venue_name,
        c.name AS city_name,
        e.event_date AS date,
        e.event_time AS time,
        e.ticket_price
    FROM events e
    LEFT JOIN event_types et ON e.event_type_id = et.id
    LEFT JOIN venues v ON e.venue_id = v.id
    LEFT JOIN cities c ON v.city_id = c.id
    WHERE 1=1
";

// Parametreleri tutacak dizi
$params = [];

// Filtreleme sorgularını ekle
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
if ($search) {
    $sql .= " AND e.name LIKE ?";
    $params[] = "%$search%";
}

// Sorguyu çalıştır
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Etkinlikler</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
</head>
<body>
    <h1>Tüm Etkinlikler</h1>

    <!-- Filtreleme Formu -->
    <form method="GET">
        <label for="event_type">Etkinlik Türü:</label>
        <select name="event_type" id="event_type">
            <option value="">Tümü</option>
            <option value="1" <?= $event_type == '1' ? 'selected' : '' ?>>Konser</option>
            <option value="2" <?= $event_type == '2' ? 'selected' : '' ?>>Tiyatro</option>
            <option value="3" <?= $event_type == '3' ? 'selected' : '' ?>>Sinema</option>
        </select>

        <label for="city">Şehir:</label>
        <input type="text" name="city" id="city" value="<?= htmlspecialchars($city) ?>">

        <label for="date">Tarih:</label>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($date) ?>">

        <label for="search">Ara:</label>
        <input type="text" name="search" id="search" value="<?= htmlspecialchars($search) ?>">

        <button type="submit">Filtrele</button>
    </form>

    <!-- Sonuçlar -->
    <h2>Sonuçlar</h2>
    <ul>
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <li>
                    <?= htmlspecialchars($event['event_name']) ?> - <?= htmlspecialchars($event['city_name']) ?> - <?= htmlspecialchars($event['date']) ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Hiç etkinlik bulunamadı.</p>
        <?php endif; ?>
    </ul>

    <div id="calendar"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($events as $event): ?>
                        {
                            title: "<?= htmlspecialchars($event['event_name']) ?>",
                            start: "<?= htmlspecialchars($event['date']) ?>T<?= htmlspecialchars($event['time']) ?>",
                        },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    alert('Etkinlik: ' + info.event.title);
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>