<?php
// config.php
$host = 'localhost';
$db = 'file_share';
$user = 'root';
$pass = 'root';

// アップロード先のディレクトリを絶対パスで指定
$storagePath = 'C:/Sites/fileShare/data/';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
