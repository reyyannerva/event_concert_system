<?php
include 'config.php'; // Veritabanı bağlantısı
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Ara</title>
    <link rel="stylesheet" href="asset_styles.css"> <!-- CSS Dosyası -->
    <script>
        // Dinamik Arama
        function searchEvent() {
            const query = document.getElementById('search').value;
            if (query.length > 2) {
                fetch(`search_event.php?q=${query}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('dynamic-results').innerHTML = data;
                    });
            } else {
                document.getElementById('dynamic-results').innerHTML = '';
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>🔍 Etkinlik Ara</h1>
    </header>
    <div class="container">
        <!-- Dinamik Arama -->
        <div>
            <label for="search">Etkinlik Ara:</label>
            <input type="text" id="search" oninput="searchEvent()" placeholder="Etkinlik adı yazın...">
            <div id="dynamic-results"></div>
        </div>

        <!-- Form ile Filtreleme -->
        <form method="get" action="">
            <label for="type">Etkinlik Türü:</label>
            <select name="type" id="type">
                <option value="">Tümü</option>
                <option value="Konser">Konser</option>
                <option value="Tiyatro">Tiyatro</option>
                <option value="Sergi">Sergi</option>
            </select>
            <button type="submit">Ara</button>
        </form>

        <!-- Sonuç Tablosu -->
        <table class="table">
            <thead>
                <tr>
                    <th>Etkinlik Adı</th>
                    <th>Tür</th>
                    <th>Şehir</th>
                    <th>Tarih</th>
                    <th>Detaylar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Form üzerinden gelen filtreleme
                if (isset($_GET['type']) && $_GET['type'] !== "") {
                    $stmt = $conn->prepare("SELECT * FROM events WHERE event_type_id = (SELECT id FROM event_types WHERE type_name = ?)");
                    $stmt->bind_param("s", $_GET['type']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $stmt = $conn->query("SELECT * FROM events");
                    $result = $stmt->get_result();
                }

                // Verileri Listeleme
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['event_type_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['venue_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
                    echo "<td><a href='event_details.php?id=" . $row['id'] . "'>Detayları Gör</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    // AJAX ile dinamik arama sorgusu
    if (isset($_GET['q'])) {
        $q = "%" . $_GET['q'] . "%";
        $sql = "SELECT name, event_date, event_time FROM events WHERE name LIKE ? ORDER BY event_date ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $q);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['event_date']) . " " . htmlspecialchars($row['event_time']) . "</p>";
            echo "</div>";
        }
        exit();
    }
    ?>
</body>
</html>
