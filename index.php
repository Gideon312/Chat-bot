<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрирование - ОАО Деталь</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            font-size: 16px; /* Уменьшен базовый размер шрифта */
            line-height: 1.5;
            color: #333;
        }
        header {
            background-color: #007BFF;
            padding: 20px; /* Уменьшен padding */
            text-align: center;
            color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0;
            font-size: 1.5rem; /* Уменьшен размер заголовка */
        }
        .container {
            max-width: 400px; /* Уменьшена максимальная ширина */
            margin: 30px auto; /* Уменьшены отступы */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007BFF;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.3rem; /* Уменьшен размер подзаголовка */
        }
        .form-group {
            margin-bottom: 15px; /* Уменьшен отступ между полями */
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 3px 3px; /* Уменьшен padding */
            margin: 5px 0; /* Уменьшены отступы */
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px; /* Уменьшен размер шрифта */
            transition: border 0.3s;
            height: 36px; /* Фиксированная высота */
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px; /* Уменьшен padding */
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; /* Уменьшен размер шрифта */
            font-weight: bold;
            transition: background-color 0.3s;
            height: 38px; /* Фиксированная высота */
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            text-align: center;
            margin-top: 12px; /* Уменьшен отступ */
            padding: 8px; /* Уменьшен padding */
            background-color: #f8d7da;
            border-radius: 4px;
            font-size: 13px; /* Уменьшен размер шрифта */
            <?php echo (isset($_GET['error']) ? 'display: block;' : 'display: none;'); ?>
        }
        .logo {
            text-align: center;
            margin-bottom: 15px; /* Уменьшен отступ */
        }
        .logo img {
            max-height: 60px; /* Уменьшен размер логотипа */
        }
    </style>
</head>
<body>

<header>
    <h1>Администрирование - ОАО "Деталь"</h1>
</header>

<div class="container">
    <div class="logo">
        <img src="logo.svg" alt="Логотип компании">
    </div>
    
    <h2>Вход в систему администрирования</h2>
    
    <div class="error">
        <?php if (isset($_GET['error'])): ?>
            Неверный логин или пароль. Пожалуйста, попробуйте снова.
        <?php endif; ?>
    </div>
    
    <form id="loginForm" action="Сервер.php" method="POST">
        <div class="form-group">
            <input type="text" name="username" placeholder="Введите ваш логин" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Введите ваш пароль" required>
        </div>
        <input type="submit" value="Войти в систему">
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = this.elements['username'].value.trim();
        const password = this.elements['password'].value.trim();
        const errorDiv = document.querySelector('.error');
        
        if (!username || !password) {
            e.preventDefault();
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Пожалуйста, заполните все поля';
        }
    });
</script>

</body>
</html>