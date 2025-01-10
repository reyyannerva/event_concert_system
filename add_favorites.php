<?php
session_start();
require 'config.php'; // Veritabanı bağlantısı

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Kullanıcı oturum açmamış.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_GET['event_id'] ?? null;

// Geçerli etkinlik ID'si kontrolü
if (!$event_id || !is_numeric($event_id)) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz etkinlik ID\'si.']);
    exit();
}

// Favori durumu kontrolü
$stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user_id, $event_id]);
$is_favorited = $stmt->fetch(PDO::FETCH_ASSOC);

// Favoriye ekleme veya kaldırma
if ($is_favorited) {
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$user_id, $event_id]);
    echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Favorilerden kaldırıldı.']);
} else {
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, event_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $event_id]);
    echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Favorilere eklendi.']);
}
?>
