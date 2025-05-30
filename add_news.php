<?php
session_start();

// Проверка авторизации и прав администратора
if (!isset($_SESSION['username']) || !($_SESSION['is_admin'] ?? false)) {
    header("Location: index.php");
    exit;
}

// Функция для сохранения новостей
function saveNews($news) {
    file_put_contents('news_data.json', json_encode($news, JSON_PRETTY_PRINT));
}

// Загрузка существующих новостей
$news = [];
if (file_exists('news_data.json')) {
    $news = json_decode(file_get_contents('news_data.json'), true);
}

// Обработка добавления новости
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_news = [
        'date' => $_POST['news_date'],
        'title' => $_POST['news_title'],
        'content' => $_POST['news_content']
    ];
    array_unshift($news, $new_news);
    saveNews($news);
    header("Location: Главный.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить новость</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 150px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добавить новую новость</h1>
        <form method="POST">
            <div class="form-group">
                <label for="news_date">Дата:</label>
                <input type="text" id="news_date" name="news_date" placeholder="Например: 11.03.2025" required>
            </div>
            <div class="form-group">
                <label for="news_title">Заголовок:</label>
                <input type="text" id="news_title" name="news_title" required>
            </div>
            <div class="form-group">
                <label for="news_content">Текст новости:</label>
                <textarea id="news_content" name="news_content" required></textarea>
            </div>
            <button type="submit">Добавить новость</button>
        </form>
    </div>
</body>
</html>