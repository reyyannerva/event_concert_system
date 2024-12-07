<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Satın Al</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <?php
        if (isset($_GET['event_id'])) {
            $event_id = $_GET['event_id'];

            // Etkinlik bilgilerini al
            $query = "SELECT events.*, venues.name AS venue_name, cities.name AS city_name, artists.name AS artist_name 
                      FROM events
                      INNER JOIN venues ON events.venue_id = venues.id
                      INNER JOIN cities ON venues.city_id = cities.id
                      INNER JOIN artists ON events.artist_id = artists.id
                      WHERE events.id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$event_id]);
            $event = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($event) {
                echo "<h1 class='text-center text-primary mb-4'>Bilet Satın Al</h1>";
                echo "<div class='p-4 bg-white shadow rounded'>";
                echo "<h3><strong>Sanatçı:</strong> " . htmlspecialchars($event['artist_name']) . "</h3>";
                echo "<p><strong>Mekan:</strong> " . htmlspecialchars($event['venue_name']) . " (" . htmlspecialchars($event['city_name']) . ")</p>";
                echo "<p><strong>Tarih:</strong> " . htmlspecialchars($event['date']) . "</p>";
                echo "<p><strong>Saat:</strong> " . htmlspecialchars($event['time']) . "</p>";
                echo "<form method='POST'>";
                echo "<button type='submit' name='buy_ticket' class='btn btn-success w-100'>Satın Al</button>";
                echo "</form>";
                echo "</div>";

                // Bilet satın alma işlemi
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_ticket'])) {
                    // Varsayılan olarak etkinlik için bir adet bilet kaydediyoruz.
                    $ticket_price = 100.00; // Örnek bilet fiyatı
                    $status = "sold";

                    $ticket_query = "INSERT INTO tickets (event_id, price, status) VALUES (?, ?, ?)";
                    $ticket_stmt = $conn->prepare($ticket_query);
                    $ticket_stmt->execute([$event_id, $ticket_price, $status]);

                    echo "<div class='alert alert-success mt-4'>Bilet başarıyla satın alındı!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Etkinlik bulunamadı.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Etkinlik ID eksik.</div>";
        }
        ?>
    </div>
</body>
</html>
