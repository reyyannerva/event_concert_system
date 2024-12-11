<?php
// Etkinlik bilgilerini veritabanından al
$event_id = $_GET['id'];
$query = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="asset_styles.css"> <!-- CSS Bağlantısı -->
</head>
<body>
    <header>
        <h1><?php echo $event['name']; ?></h1>
    </header>
    <div class="container">
        <p><?php echo $event['description']; ?></p>
        <p>Date: <?php echo $event['date']; ?></p>
        <p>Location: <?php echo $event['location']; ?></p>

        <!-- Harita -->
        <div id="map" style="width: 100%; height: 400px; margin-top: 20px;"></div>
        <script>
            function initMap() {
                const location = { lat: <?php echo $event['latitude']; ?>, lng: <?php echo $event['longitude']; ?> };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 15,
                    center: location,
                });
                new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
    </div>
</body>
</html>
