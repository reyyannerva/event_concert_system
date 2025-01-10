<?php
require 'config.php'; // Veritabanı bağlantısı

$type = $_GET['type'] ?? '';
$term = $_GET['term'] ?? '';

if ($type === 'event_type') {
    $query = "SELECT DISTINCT type_name FROM event_types WHERE type_name LIKE :term LIMIT 10";
} elseif ($type === 'city') {
    $query = "SELECT DISTINCT name FROM cities WHERE name LIKE :term LIMIT 10";
} else {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare($query);
$stmt->execute([':term' => '%' . $term . '%']);
$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($results);
?>
