<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center text-primary mb-4">Etkinlik Ekle</h1>
        <form action="" method="POST" class="p-4 bg-white shadow rounded">
            <div class="mb-3">
                <label for="venue" class="form-label">Mekan:</label>
                <select id="venue" name="venue_id" class="form-select" required>
                    <?php
                    $venues = $conn->query("SELECT id, name FROM venues")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($venues as $venue) {
                        echo "<option value='{$venue['id']}'>{$venue['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="artist" class="form-label">Sanatçı:</label>
                <select id="artist" name="artist_id" class="form-select" required>
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
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Saat:</label>
                <input type="time" id="time" name="time" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ekle</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $venue_id = $_POST["venue_id"];
            $artist_id = $_POST["artist_id"];
            $date = $_POST["date"];
            $time = $_POST["time"];

            $sql = "INSERT INTO events (venue_id, artist_id, date, time) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute([$venue_id, $artist_id, $date, $time])) {
                echo "<div class='alert alert-success mt-4'>Etkinlik başarıyla eklendi!</div>";
            } else {
                echo "<div class='alert alert-danger mt-4'>Bir hata oluştu.</div>";
            }
        }
        ?>
    </div>
</body>
</html>


