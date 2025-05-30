<?php
session_start();

$users = [
    'admin' => 'password123', // Логин администратора
    'user' => 'userpass'      // Логин пользователя
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = ($username === 'admin'); // Флаг админа
        
        header("Location: Главный.php");
        exit;
    } else {
        header("Location: index.php?error=1");
        exit;
    }
}
?>