<?php
include 'config.php';

$kategori = $_GET['kategori'] ?? '';
$query = "SELECT * FROM etkinlikler";
if ($kategori) {
    $query .= " WHERE kategori = '$kategori'";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrelenmiş Etkinlikler</title>
    <link rel="stylesheet" href="asset_styles.css">
</head>
<body>
    <header class="hero-section">
        <h1>Filtrelenmiş Etkinlikler</h1>
    </header>
    <main class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Etkinlik Adı</th>
                    <th>Tarih</th>
                    <th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($etkinlik = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $etkinlik['ad']; ?></td>
                    <td><?php echo $etkinlik['tarih']; ?></td>
                    <td><?php echo $etkinlik['kategori']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
