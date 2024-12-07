
<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Ara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center text-success mb-4">Etkinlik Ara</h1>
        <form action="" method="GET" class="p-4 bg-white shadow rounded">
            <div class="mb-3">
                <label for="city" class="form-label">Şehir:</label>
                <select id="city" name="city_id" class="form-select">
                    <option value="">Tüm Şehirler</option>
                    <?php
                    $cities = $conn->query("SELECT id, name FROM cities")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($cities as $city) {
                        echo "<option value='{$city['id']}'>{$city['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="artist" class="form-label">Sanatçı:</label>
                <select id="artist" name="artist_id" class="form-select">
                    <option value="">Tüm Sanatçılar</option>
                    <?php
                    $artists = $conn->query("SELECT id, name FROM artists")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($artists as $artist) {
                        echo "<option value='{$artist['id']}'>{$artist['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Tarih:</label>
                <input type="date" id="date" name="date" class="form-control">
            </div>
            <button type="submit" class="btn btn-success w-100">Ara</button>
        </form>

        <h2 class="mt-5">Sonuçlar:</h2>
        <div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
                $city_id = $_GET['city_id'] ?? '';
                $artist_id = $_GET['artist_id'] ?? '';
                $date = $_GET['date'] ?? '';

                $query = "SELECT events.*, venues.name AS venue_name, cities.name AS city_name, artists.name AS artist_name
                          FROM events
                          INNER JOIN venues ON events.venue_id = venues.id
                          INNER JOIN cities ON venues.city_id = cities.id
                          INNER JOIN artists ON events.artist_id = artists.id
                          WHERE 1=1";

                $params = [];
                if (!empty($city_id)) {
                    $query .= " AND cities.id = ?";
                    $params[] = $city_id;
                }
                if (!empty($artist_id)) {
                    $query .= " AND artists.id = ?";
                    $params[] = $artist_id;
                }
                if (!empty($date)) {
                    $query .= " AND events.date = ?";
                    $params[] = $date;
                }

                $stmt = $conn->prepare($query);
                $stmt->execute($params);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($results) > 0) {
                    foreach ($results as $result) {
                        echo "<p><strong>Etkinlik:</strong> {$result['artist_name']} - {$result['venue_name']} ({$result['city_name']})<br>";
                        echo "<strong>Tarih:</strong> {$result['date']}<br>";
                        echo "<strong>Saat:</strong> {$result['time']}<br></p>";
                    }
                } else {
                    echo "<p>Hiçbir etkinlik bulunamadı.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
