<?php
$host = "localhost";
$dbname = "concert_system";
$username = "root"; // Varsayılan kullanıcı adı
$password = "";     // Varsayılan şifre

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
