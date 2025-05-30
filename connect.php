<?php
$host = 'localhost';
$dbname = 'detal';
$user = 'detal_user';
$pass = 'secure_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Успешное подключение к базе данных!";
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>