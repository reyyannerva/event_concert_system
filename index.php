<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konser & Etkinlik Bilgi Sistemi</title>
    <link rel="stylesheet" href="asset_styles.css">
</head>
<body>
    <header class="hero-section">
        <h1>Konser & Etkinlik Bilgi Sistemi</h1>
        <p>En iyi etkinlikleri keşfedin ve eğlenin!🥳</p>
        <div class="navigation">
            <a href="filtered_events.php" class="nav-button">Etkinlikleri Filtrele</a>
            
            <a href="all_events.php" class="nav-button">Tüm Etkinlikleri Gör</a>
        </div>
    </header>

    <main class="container">
        <h2>Öne Çıkan Etkinlikler</h2>
        <div class="event-card">
            <img src="images/event1.jpg" alt="Müzik Gecesi">
            <h3>Müzik Gecesi</h3>
            <p>Harika bir canlı müzik deneyimi yaşayın.</p>
            <a href="event_details.php?id=1" class="filter-button">Detayları Gör</a>
        </div>
        <div class="event-card">
            <img src="images/event2.jpg" alt="Sanat Sergisi">
            <h3>Sanat Sergisi</h3>
            <p>Eşsiz sanat eserlerini keşfedin.</p>
            <a href="event_details.php?id=2" class="filter-button">Detayları Gör</a>
        </div>
    </main>
</body>
</html>


