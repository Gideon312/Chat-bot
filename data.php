<?php
// Настройка кодировки
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Настройки подключения
$servername = "localhost";
$username = "detal_user";
$password = "secure_password";
$dbname = "detal";

// Настройки администратора
$admin_login = "admin_2";
$admin_password = "admin123"; // В реальном проекте используйте хеширование паролей!

// Старт сессии
session_start();

// Обработка выхода
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    // Перенаправляем на текущую страницу без параметров
    $redirect_url = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: ".$redirect_url);
    exit;
}

// Обработка авторизации
if (isset($_POST['admin_login'])) {
    if ($_POST['login'] == $admin_login && $_POST['password'] == $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: ".$_SERVER['REQUEST_URI']);
        exit;
    } else {
        $login_error = "Неверный логин или пароль";
    }
}

// Проверка авторизации администратора
$admin_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Создание соединения с БД
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Ошибка подключения: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

// Проверяем режим управления (доступен только для авторизованных)
$manage_mode = $admin_logged_in && isset($_GET['manage']) && $_GET['manage'] == '1';

// Обработка добавления строки (только в режиме управления)
if ($manage_mode && isset($_POST['add_row'])) {
    $table = $conn->real_escape_string($_POST['table']);
    $columns = $conn->query("SHOW COLUMNS FROM `$table`");
    
    $fields = [];
    $values = [];
    while ($col = $columns->fetch_assoc()) {
        if ($col['Field'] != 'id') {
            $fields[] = $col['Field'];
            $values[] = "'" . $conn->real_escape_string($_POST[$col['Field']] ?? '') . "'";
        }
    }
    
    $conn->query("INSERT INTO `$table` (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")");
    header("Location: ?table=$table&manage=1");
    exit;
}

// Обработка сохранения изменений (только в режиме управления)
if ($manage_mode && isset($_POST['save_changes'])) {
    $table = $conn->real_escape_string($_POST['table']);
    $primary_key = $conn->real_escape_string($_POST['primary_key']);
    
    // Обновление измененных строк
    if (isset($_POST['data'])) {
        foreach ($_POST['data'] as $id => $row) {
            $updates = [];
            foreach ($row as $field => $value) {
                $field = $conn->real_escape_string($field);
                $value = $conn->real_escape_string($value);
                $updates[] = "`$field` = '$value'";
            }
            
            if (!empty($updates)) {
                $conn->query("UPDATE `$table` SET " . implode(', ', $updates) . " WHERE `$primary_key` = " . (int)$id);
            }
        }
    }
    
    // Удаление отмеченных строк
    if (isset($_POST['delete_rows'])) {
        $ids_to_delete = array_map('intval', $_POST['delete_rows']);
        $ids_str = implode(',', $ids_to_delete);
        $conn->query("DELETE FROM `$table` WHERE `$primary_key` IN ($ids_str)");
    }
    
    header("Location: ?table=$table&manage=1");
    exit;
}

// Получаем список таблиц
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) $tables[] = $row[0];

// Текущая таблица
$current_table = $tables[0];
if (isset($_GET['table']) && in_array($_GET['table'], $tables)) {
    $current_table = $_GET['table'];
}

// Получаем структуру таблицы
$columns = [];
$primary_key = 'id';
$result = $conn->query("SHOW COLUMNS FROM `$current_table`");
while ($col = $result->fetch_assoc()) {
    $columns[] = $col;
    if ($col['Key'] == 'PRI') $primary_key = $col['Field'];
}

// Получаем данные с сортировкой
$data = [];
$result = $conn->query("SELECT * FROM `$current_table` ORDER BY `$primary_key`");
while ($row = $result->fetch_assoc()) $data[] = $row;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $admin_logged_in ? 'Управление' : 'Просмотр' ?> базы данных</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .editable { cursor: pointer; }
        .editable:hover { background-color: #ffeeba; }
        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 50%; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .toolbar { margin: 15px 0; }
        button { padding: 8px 15px; cursor: pointer; margin-right: 10px; }
        .to-delete { background-color: #ffdddd; }
        .manage-btn { background-color: #4CAF50; color: white; border: none; }
        .view-btn { background-color: #f44336; color: white; border: none; }
        .login-form { margin: 20px 0; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; width: 300px; }
        .login-form input { margin: 5px 0; width: 100%; padding: 8px; }
        .login-form button { width: 100%; }
        .error { color: red; }
        .home-btn { background-color: #2196F3; color: white; border: none; }
    </style>
</head>
<body>
    <h1><?= $admin_logged_in ? 'Управление базой данных' : 'Просмотр базы данных' ?></h1>
    
    <div>
        <strong>Таблицы:</strong>
        <?php foreach ($tables as $table): ?>
            <a href="?table=<?= $table ?><?= $manage_mode ? '&manage=1' : '' ?>"><?= $table ?></a> |
        <?php endforeach; ?>
    </div>
    
    <div class="toolbar">
        <?php if ($admin_logged_in): ?>
            <?php if ($manage_mode): ?>
                <button id="addRowBtn">Добавить строку</button>
                <button id="deleteRowsBtn">Удалить отмеченные</button>
                <button id="saveChangesBtn">Сохранить изменения</button>
                <a href="?table=<?= $current_table ?>" class="view-btn">Режим просмотра</a>
            <?php else: ?>
                <a href="?table=<?= $current_table ?>&manage=1" class="manage-btn">Управлять БД</a>
            <?php endif; ?>
            <a href="?logout=1" class="view-btn">Выйти из роли администратора</a>
            <a href="Главный.php" class="home-btn">На главную</a>
        <?php else: ?>
            <button id="manageDbBtn" class="manage-btn">Управлять БД</button>
            <a href="Главный.php" class="home-btn">На главную</a>
        <?php endif; ?>
    </div>
    
    <!-- Форма авторизации (скрытая по умолчанию) -->
    <?php if (!$admin_logged_in): ?>
    <div id="loginForm" class="login-form" style="display: none;">
        <h3>Авторизация администратора</h3>
        <?php if (isset($login_error)): ?>
            <div class="error"><?= $login_error ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="admin_login" value="1">
            <div>
                <label>Логин:</label>
                <input type="text" name="login" required>
            </div>
            <div>
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>
    <?php endif; ?>
    
    <h2>Таблица: <?= $current_table ?></h2>
    
    <?php if ($manage_mode): ?>
        <form id="editForm" method="post">
            <input type="hidden" name="save_changes" value="1">
            <input type="hidden" name="table" value="<?= $current_table ?>">
            <input type="hidden" name="primary_key" value="<?= $primary_key ?>">
    <?php endif; ?>
    
        <table>
            <thead>
                <tr>
                    <?php if ($manage_mode): ?>
                        <th width="30">Удалить</th>
                    <?php endif; ?>
                    <?php foreach ($columns as $column): ?>
                        <th><?= $column['Field'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr id="row-<?= $row[$primary_key] ?>">
                        <?php if ($manage_mode): ?>
                            <td><input type="checkbox" name="delete_rows[]" value="<?= $row[$primary_key] ?>" 
                                      onchange="toggleDeleteRow(this)"></td>
                        <?php endif; ?>
                        <?php foreach ($columns as $column): ?>
                            <td <?= $manage_mode ? 'class="editable" data-id="'.$row[$primary_key].'" data-field="'.$column['Field'].'" onclick="editCell(this)"' : '' ?>>
                                <?= htmlspecialchars($row[$column['Field']] ?? '') ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
    <?php if ($manage_mode): ?>
        </form>
    <?php endif; ?>
    
    <!-- Модальное окно для добавления строки -->
    <?php if ($manage_mode): ?>
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Добавить новую строку</h2>
            <form method="post">
                <input type="hidden" name="table" value="<?= $current_table ?>">
                <input type="hidden" name="add_row" value="1">
                
                <?php foreach ($columns as $column): ?>
                    <?php if ($column['Field'] != 'id'): ?>
                        <div style="margin-bottom: 10px;">
                            <label><?= $column['Field'] ?>:</label>
                            <input type="text" name="<?= $column['Field'] ?>" style="width: 100%;">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <button type="submit">Добавить</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <script>
        // Показ формы авторизации
        document.getElementById('manageDbBtn')?.addEventListener('click', function() {
            document.getElementById('loginForm').style.display = 'block';
        });
        
        <?php if ($manage_mode): ?>
        // Редактирование ячейки
        function editCell(cell) {
            const id = cell.getAttribute('data-id');
            const field = cell.getAttribute('data-field');
            const value = cell.textContent.trim();
            
            cell.innerHTML = `<input type="text" 
                                  value="${value}" 
                                  data-id="${id}" 
                                  data-field="${field}"
                                  onblur="saveCell(this)"
                                  onkeypress="if(event.keyCode==13) saveCell(this)">`;
            
            cell.querySelector('input').focus();
        }
        
        // Сохранение ячейки
        function saveCell(input) {
            const cell = input.parentNode;
            const newValue = input.value.trim();
            cell.textContent = newValue;
            
            // Добавляем скрытые поля в форму для сохранения
            const form = document.getElementById('editForm');
            const id = input.getAttribute('data-id');
            const field = input.getAttribute('data-field');
            
            let inputField = document.querySelector(`input[name="data[${id}][${field}]"]`);
            if (!inputField) {
                inputField = document.createElement('input');
                inputField.type = 'hidden';
                inputField.name = `data[${id}][${field}]`;
                form.appendChild(inputField);
            }
            inputField.value = newValue;
        }
        
        // Отметка строки для удаления
        function toggleDeleteRow(checkbox) {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.classList.add('to-delete');
            } else {
                row.classList.remove('to-delete');
            }
        }
        
        // Открытие модального окна
        document.getElementById('addRowBtn')?.addEventListener('click', function() {
            document.getElementById('addModal').style.display = 'block';
        });
        
        // Закрытие модального окна
        document.querySelector('.close')?.addEventListener('click', function() {
            document.getElementById('addModal').style.display = 'none';
        });
        
        // Закрытие при клике вне окна
        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) {
                document.getElementById('addModal').style.display = 'none';
            }
        }
        
        // Сохранение изменений
        document.getElementById('saveChangesBtn')?.addEventListener('click', function() {
            document.getElementById('editForm').submit();
        });
        
        // Удаление отмеченных строк
        document.getElementById('deleteRowsBtn')?.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="delete_rows[]"]:checked');
            if (checkboxes.length === 0) {
                alert('Выберите строки для удаления');
                return;
            }
            
            if (confirm(`Вы уверены, что хотите удалить ${checkboxes.length} строк?`)) {
                document.getElementById('editForm').submit();
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>

<?php $conn->close(); ?>