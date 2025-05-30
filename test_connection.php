<?php
// Настройки подключения
$host = 'localhost';
$dbname = 'detal';
$user = 'detal_user';
$pass = 'secure_password';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Тест подключения к MySQL</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: #3c763d; background-color: #dff0d8; padding: 15px; border-radius: 4px; }
        .error { color: #a94442; background-color: #f2dede; padding: 15px; border-radius: 4px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Тест подключения к базе данных</h1>
    
    <?php
    try {
        // Подключение
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo '<div class="success">Успешное подключение к базе данных!</div>';
        
        // Получаем список таблиц
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo '<h2>Таблицы в базе:</h2>';
            echo '<ul>';
            foreach ($tables as $table) {
                echo '<li>' . htmlspecialchars($table) . '</li>';
                
                // Для первой таблицы покажем структуру
                if ($table === reset($tables)) {
                    $columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
                    echo '<pre>Структура таблицы ' . htmlspecialchars($table) . ":\n";
                    print_r($columns);
                    echo '</pre>';
                }
            }
            echo '</ul>';
        } else {
            echo '<div class="error">В базе данных нет таблиц</div>';
        }
        
    } catch (PDOException $e) {
        echo '<div class="error">Ошибка подключения: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<h3>Рекомендации:</h3>';
        echo '<ol>';
        echo '<li>Проверьте запущен ли сервер MySQL</li>';
        echo '<li>Убедитесь в правильности логина/пароля</li>';
        echo '<li>Проверьте существует ли база данных "' . htmlspecialchars($dbname) . '"</li>';
        echo '<li>Убедитесь что пользователь "' . htmlspecialchars($user) . '" имеет права доступа</li>';
        echo '</ol>';
        
        // Дополнительная диагностика
        echo '<h3>Диагностическая информация:</h3>';
        echo '<pre>';
        echo "Попытка подключения к:\n";
        echo "Хост: $host\n";
        echo "База: $dbname\n";
        echo "Пользователь: $user\n";
        echo "Пароль: " . str_repeat('*', strlen($pass)) . "\n\n";
        
        // Проверка только подключения без выбора БД
        try {
            $pdo_no_db = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
            echo "Подключение к серверу без выбора БД - УСПЕШНО\n";
            
            // Проверка существования БД
            $stmt = $pdo_no_db->query("SHOW DATABASES LIKE '$dbname'");
            $dbs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($dbs) > 0) {
                echo "База '$dbname' существует на сервере\n";
            } else {
                echo "База '$dbname' НЕ существует на сервере\n";
            }
            
        } catch (PDOException $e) {
            echo "Ошибка подключения к серверу: " . $e->getMessage() . "\n";
        }
        echo '</pre>';
    }
    ?>
</body>
</html>