<?php
// authenticate.php
session_start();

// Проверяем данные формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    
    // Здесь должна быть реальная проверка логина/пароля из базы данных
    // Для примера используем простую проверку
    $valid_credentials = false;
    
    // Пример проверки (замените на реальную проверку)
    if ($username === 'admin' && $password === 'admin123') {
        $valid_credentials = true;
        $role = 'admin'; // Принудительно устанавливаем роль админа для этого пользователя
    } elseif ($username === 'user' && $password === 'user123') {
        $valid_credentials = true;
        $role = 'user'; // Принудительно устанавливаем роль пользователя
    }
    
    if ($valid_credentials) {
        // Сохраняем данные в сессии
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        
        // Перенаправляем на защищенную страницу
        header('Location: data.php');
        exit;
    } else {
        // Перенаправляем обратно с ошибкой
        header('Location: index.php?error=1');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>