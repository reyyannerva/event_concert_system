<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Etkinlikler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center text-info mb-4">Tüm Etkinlikler</h1>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Sanatçı</th>
                    <th>Mekan</th>
                    <th>Şehir</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Tüm etkinlikleri al
                $query = "SELECT events.*, venues.name AS venue_name, cities.name AS city_name, artists.name AS artist_name 
                          FROM events
                          INNER JOIN venues ON events.venue_id = venues.id
                          INNER JOIN cities ON venues.city_id = cities.id
                          INNER JOIN artists ON events.artist_id = artists.id";
                $stmt = $conn->query($query);
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($events as $index => $event) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>" . htmlspecialchars($event['artist_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['venue_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['city_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['time']) . "</td>";
                    echo "<td><a href='buy_ticket.php?event_id={$event['id']}' class='btn btn-success btn-sm'>Bilet Al</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
