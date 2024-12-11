<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Satın Al</title>
    <link rel="stylesheet" href="asset_styles.css">
</head>
<body>
    <header>
        <h1>🎟️ Bilet Satın Al</h1>
    </header>
    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                echo "<h2>" . $row['name'] . "</h2>";
                echo "<p>Bilet başarıyla satın alındı! 🎉</p>";
            } else {
                echo "<p>Etkinlik bulunamadı!</p>";
            }
        }
        ?>
    </div>
</body>
</html>
