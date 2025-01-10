<?php
// Veritabanı Bağlantı Bilgileri
$dsn = 'mysql:host=localhost;dbname=concert_event_system;charset=utf8';
$username = 'root'; // Veritabanı kullanıcı adı
$password = ''; // Veritabanı şifresi

try {
    // PDO bağlantısını oluştur ve $conn olarak adlandır
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Hata durumunda log dosyasına yaz ve kullanıcıya basit bir mesaj göster
    error_log("Veritabanı bağlantısı hatası: " . $e->getMessage(), 3, 'error_log.txt');
    die("Veritabanı bağlantısı başarısız. Lütfen daha sonra tekrar deneyin.");
}
?>
