<?php
session_start();
require 'config.php';

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Kullanıcı bilgilerini al
$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Kullanıcı bulunamadı.";
    exit;
}

// Kullanıcının aldığı biletleri al
$stmt_tickets = $conn->prepare("SELECT t.id AS ticket_id, e.name AS event_name, e.event_date, e.event_time, e.venue_id FROM tickets t JOIN events e ON t.event_id = e.id WHERE t.user_id = ?");
$stmt_tickets->execute([$user_id]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

// Kullanıcının favorilerine eklediği etkinlikleri al
$stmt_favorites = $conn->prepare("SELECT e.id AS event_id, e.name AS event_name, e.event_date, e.event_time FROM favorites f JOIN events e ON f.event_id = e.id WHERE f.user_id = ?");
$stmt_favorites->execute([$user_id]);
$favorites = $stmt_favorites->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #1c92d2, #f2fcfe);
            color: white;
            padding-top: 70px;
        }
        .navbar {
            background: linear-gradient(to right, #ff758c, #ff7eb3);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }
        .profile-container {
            background: white;
            color: black;
            border-radius: 15px;
            padding: 30px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
        .profile-container h1 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            color: #ff758c;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .profile-info p {
            margin: 0;
            font-size: 1.2rem;
        }
        .table th {
            background: #ff758c;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
            color: #333;
        }
        .btn-detail {
            background: linear-gradient(to right, #1c92d2, #6dd5ed);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
        }
        .btn-detail:hover {
            background: linear-gradient(to right, #6dd5ed, #1c92d2);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">🎟️ Konser & Etkinlik Sistemi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="all_events.php">Tüm Etkinlikler</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profilim</a></li>
                <li class="nav-item"><a class="btn btn-danger text-white" href="logout.php">Çıkış Yap</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="profile-container">
        <h1>Profilim</h1>
        <div class="profile-info">
            <p><strong>Kullanıcı Adı:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>E-posta:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Hesap Oluşturma Tarihi:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
        </div>

        <h2 class="section-title">Aldığım Biletler</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Etkinlik Adı</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Detay</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tickets) > 0): ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?= htmlspecialchars($ticket['event_name']) ?></td>
                            <td><?= htmlspecialchars($ticket['event_date']) ?></td>
                            <td><?= htmlspecialchars($ticket['event_time']) ?></td>
                            <td><a href="event_details.php?id=<?= htmlspecialchars($ticket['venue_id']) ?>" class="btn-detail">Detay Gör</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Hiç biletiniz yok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2 class="section-title">Favorilerim</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Etkinlik Adı</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Detay</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($favorites) > 0): ?>
                    <?php foreach ($favorites as $favorite): ?>
                        <tr>
                            <td><?= htmlspecialchars($favorite['event_name']) ?></td>
                            <td><?= htmlspecialchars($favorite['event_date']) ?></td>
                            <td><?= htmlspecialchars($favorite['event_time']) ?></td>
                            <td><a href="event_details.php?id=<?= htmlspecialchars($favorite['event_id']) ?>" class="btn-detail">Detay Gör</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Hiç favoriniz yok.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
