<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$is_admin = $_SESSION['is_admin'] ?? false;

// Загрузка новостей
$news = [];
if (file_exists('news_data.json')) {
    $news = json_decode(file_get_contents('news_data.json'), true);
    if ($news === null) {
        $news = []; // Если файл поврежден, используем пустой массив
    }
}

// Обработка удаления новостей
if ($is_admin && isset($_POST['delete_news']) && isset($_POST['news_to_delete'])) {
    $news_to_keep = [];
    foreach ($news as $index => $item) {
        if (!in_array($index, $_POST['news_to_delete'])) {
            $news_to_keep[] = $item;
        }
    }
    file_put_contents('news_data.json', json_encode($news_to_keep, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Обработка переключения языка
$current_language = 'ru';
if (isset($_GET['lang'])) {
    $current_language = $_GET['lang'] === 'en' ? 'en' : 'ru';
    setcookie('lang', $current_language, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['lang'])) {
    $current_language = $_COOKIE['lang'];
}


// Полный массив переводов для всех элементов страницы
$translations = [
    'ru' => [
        'title' => 'УПКБ Деталь',
        'search_placeholder' => 'Поиск по сайту...',
        'about' => 'О предприятии',
        'about_text' => 'Уральское проектно-конструкторское бюро "Деталь" является ведущим предприятием России по разработке и производству радиовысотомеров и радиовысотомерных систем для авиационной и космической техники.',
        'more' => 'Подробнее',
        'news' => 'Новости',
        'add_news' => 'Добавить новость',
        'contact' => 'Контактная информация',
        'copyright' => '© 2025 г.Троицк.',
        'nav_about' => 'О предприятии',
        'nav_mission' => 'Миссия предприятия',
        'nav_main' => 'Основное',
        'nav_history' => 'История предприятия',
        'nav_products' => 'Продукция',
        'nav_small' => 'Радиовысотомеры малых высот',
        'nav_medium' => 'Радиовысотомеры средних и больших высот',
        'nav_board' => 'Бортовые РЛС',
        'nav_development' => 'Разработки',
        'nav_production' => 'Производство',
        'nav_data' => 'Данные',
        'login_as' => 'Вы вошли как:',
        'logout' => 'Выйти',
        'login' => 'Войти',
        'nothing_found' => 'Ничего не найдено',
        'chatbot_title' => 'Чат-бот',
        'chatbot_placeholder' => 'Введите сообщение...',
        'chatbot_send' => 'Отправить'
    ],
    'en' => [
        'title' => 'UPKB Detail',
        'search_placeholder' => 'Search the site...',
        'about' => 'About the company',
        'about_text' => 'Ural Design Bureau "Detail" is the leading Russian enterprise for the development and production of radio altimeters and radio altimeter systems for aviation and space technology.',
        'more' => 'Read more',
        'news' => 'News',
        'add_news' => 'Add news',
        'contact' => 'Contact information',
        'copyright' => '© 2024 Company XYZ. All rights reserved.',
        'nav_about' => 'About company',
        'nav_mission' => 'Company mission',
        'nav_main' => 'Main',
        'nav_history' => 'Company history',
        'nav_products' => 'Products',
        'nav_small' => 'Small altitude radio altimeters',
        'nav_medium' => 'Medium and high altitude radio altimeters',
        'nav_board' => 'Onboard radar systems',
        'nav_development' => 'Developments',
        'nav_production' => 'Production',
        'nav_data' => 'Data',
        'login_as' => 'Logged in as:',
        'logout' => 'Logout',
        'login' => 'Login',
        'nothing_found' => 'Nothing found',
        'chatbot_title' => 'Chatbot',
        'chatbot_placeholder' => 'Type a message...',
        'chatbot_send' => 'Send'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= $current_language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>УПКБ Деталь</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="header-banner" id="headerBanner">
    <div class="header-bg"></div>
    <a href="Главный.php" class="site-logo-link" aria-label="Перейти на главную страницу">
        <img src="logo.svg" alt="Логотип компании" class="site-logo">
    </a>
    <div class="header-content">
        <h1>Деталь</h1>
        <h2>Уральское проектно-конструкторское бюро</h2>
    </div>
</div>
<!-- Вторая шапка с навигацией -->
<div class="nav-header">
    <nav>
        <div class="nav-items">
            <div class="nav-item">
                <a href="#about"><?= $translations[$current_language]['nav_about'] ?></a>
                <div class="dropdown">
                    <a href="Миссия.html"><?= $translations[$current_language]['nav_mission'] ?></a>
                    <div class="dropdown-divider"></div>
                    <a href="Основное.html"><?= $translations[$current_language]['nav_main'] ?></a>
                    <div class="dropdown-divider"></div>
                    <a href="История.html"><?= $translations[$current_language]['nav_history'] ?></a>
                    <div class="dropdown-divider"></div>
                    <a href="#contact"><?= $translations[$current_language]['contact'] ?></a>
                </div>
            </div>
            
            <div class="nav-item">
                <a href="Производство.html"><?= $translations[$current_language]['nav_production'] ?></a>
            </div>
            <div class="nav-item">
                <a href="Продукция.html"><?= $translations[$current_language]['nav_products'] ?></a>
            </div>
            <div class="nav-item">
                <a href="#news"><?= $translations[$current_language]['news'] ?></a>
            </div>
        </div>

        <!-- Правая часть с элементами управления -->
        <div class="nav-controls">
            <!-- Кнопка поиска -->
            <button class="search-btn" id="search-toggle">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Переключатель языка -->
            <div class="language-switcher">
                <button class="language-btn <?= $current_language === 'ru' ? 'active' : '' ?>" 
                        onclick="changeLanguage('ru')">RU</button>
                <button class="language-btn <?= $current_language === 'en' ? 'active' : '' ?>" 
                        onclick="changeLanguage('en')">EN</button>
            </div>
            
            <!-- Блок авторизации -->
            <div class="auth-section">
                <?php if(isset($_SESSION['username'])): ?>
                    <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="index.php" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Скрытая форма поиска -->
    <div class="search-container hidden" id="search-container">
        <form id="search-form">
            <input type="text" id="search-input" 
                   placeholder="<?= $translations[$current_language]['search_placeholder'] ?>">
            <button type="submit" class="search-submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<!-- Основное содержимое страницы -->
<div class="container">
    <section id="intro">
        <h2><?= $translations[$current_language]['about'] ?></h2>
        <p><?= $translations[$current_language]['about_text'] ?></p>
        <p><a href="Основное.html" class="btn"><?= $translations[$current_language]['more'] ?></a></p>
    </section>

    <section id="news">
        <h2><?= $translations[$current_language]['news'] ?></h2>
        
        <?php if ($is_admin): ?>
        <a href="add_news.php" class="add-news-btn" target="_blank"><?= $translations[$current_language]['add_news'] ?></a>
        <?php endif; ?>
        
        <!-- Новость 1 с переводом -->
        <div class="news">
            <p><strong><?= $current_language === 'ru' ? 'Дата:' : 'Date:' ?></strong> 11.03.2025</p>
            <p><strong><?= $current_language === 'ru' ? 'Заголовок:' : 'Title:' ?></strong> 
                <?= $current_language === 'ru' ? 
                'Более 1 млрд рублей Ростех инвестирует в передовые инженерные школы в текущем году.' : 
                'Rostec to invest over 1 billion rubles in advanced engineering schools this year.' ?>
            </p>
            <p>
                <?= $current_language === 'ru' ? 
                'Организации Ростеха в 2025 году профинансируют передовые инженерные школы (ПИШ) более чем на 1 млрд рублей.' : 
                'Rostec organizations will finance advanced engineering schools (AES) with over 1 billion rubles in 2025.' ?>
            </p>
        </div>

        <!-- Новость 2 с переводом -->
        <div class="news">
            <p><strong><?= $current_language === 'ru' ? 'Дата:' : 'Date:' ?></strong> 10.03.2025</p>
            <p><strong><?= $current_language === 'ru' ? 'Заголовок:' : 'Title:' ?></strong> 
                <?= $current_language === 'ru' ? 
                'Завершены работы по разделке кормовой части танкера «Волгонефть-239»' : 
                'Completion of stern section dismantling of tanker "Volgoneft-239"' ?>
            </p>
            <p>
                <?= $current_language === 'ru' ? 
                'Специалисты закончили работы по разделке кормовой части судна, севшего на мель у мыса Панагия. На полную разрезку судна потребовалось чуть больше месяца. Первоначальным планом были предусмотрены сроки 2 месяца.' : 
                'Specialists have completed the dismantling of the stern section of the vessel that ran aground near Cape Panagia. The complete cutting of the vessel took just over a month. The original plan allowed for 2 months.' ?>
            </p>
        </div>

        <!-- Новость 3 с переводом -->
        <div class="news">
            <p><strong><?= $current_language === 'ru' ? 'Дата:' : 'Date:' ?></strong> 06.03.2025</p>
            <p><strong><?= $current_language === 'ru' ? 'Заголовок:' : 'Title:' ?></strong> 
                <?= $current_language === 'ru' ? 
                'В российских городах-миллионниках 9 мая откроют парки и проведут парады' : 
                'Victory Day parades and park openings planned in Russian cities on May 9' ?>
            </p>
            <p>
                <?= $current_language === 'ru' ? 
                'МОСКВА, 5 марта. /ТАСС/. Военные парады и шествия "Бессмертного полка" пройдут 9 мая во многих городах-миллионниках России - от Санкт-Петербурга до Красноярска, сообщили ТАСС в правительствах регионов и муниципалитетов. На эту дату также запланированы салюты, открытие обновленных музеев, инсталляций и парков Победы.' : 
                'MOSCOW, March 5. /TASS/. Military parades and "Immortal Regiment" marches will take place on May 9 in many Russian cities with populations over one million - from St. Petersburg to Krasnoyarsk, according to regional and municipal governments. Fireworks, openings of renovated museums, installations, and Victory Parks are also planned for this date.' ?>
            </p>
        </div>
        
        <?php foreach ($news as $item): ?>
        <div class="news">
            <p><strong><?= $current_language === 'ru' ? 'Дата:' : 'Date:' ?></strong> <?= htmlspecialchars($item['date']) ?></p>
            <p><strong><?= $current_language === 'ru' ? 'Заголовок:' : 'Title:' ?></strong> <?= htmlspecialchars($item['title']) ?></p>
            <p><?= nl2br(htmlspecialchars($item['content'])) ?></p>
        </div>
        <?php endforeach; ?>
    </section>

    <section id="contact">
        <h2><?= $translations[$current_language]['contact'] ?></h2>
        <p><?= $current_language === 'ru' ? 'Адрес:' : 'Address:' ?> Россия, 623409, г. Каменск-Уральский Свердловской области, ул. Пионерская, 8</p>
        <p><?= $current_language === 'ru' ? 'Отдел кадров:' : 'HR department:' ?> +7 (343) 933-92-16</p>
        <p><?= $current_language === 'ru' ? 'Начальник управления по работе с персоналом:' : 'Head of HR department:' ?> +7 (343) 937-58-54</p>
        <p><?= $current_language === 'ru' ? 'E-Mail:' : 'Email:' ?> upkb@upkb.ru, upkb@nexcom.ru</p>

        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2206.9683894360596!2d61.889909276632956!3d56.41660767333707!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x43c6d9ef95da2e57%3A0xf80e93b48ec57773!2z0JTQldCi0JDQm9CsLCDRg9GA0LDQu9GM0YHQutC-0LUg0L_RgNC-0LXQutGC0L3Qvi3QutC-0L3RgdGC0YDRg9C60YLQvtGA0YHQutC-0LUg0LHRjtGA0L4!5e0!3m2!1sru!2sru!4v1744064998633!5m2!1sru!2sru" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>
</div>

<!-- Чат-бот -->
<div id="chatbot-container">
    <div id="chatbot-header">
        <span><?= $translations[$current_language]['chatbot_title'] ?></span>
        <button id="chatbot-close">×</button>
    </div>
    <div id="chatbot-messages"></div>
    <div id="chatbot-input">
        <input type="text" id="chatbot-user-input" placeholder="<?= $translations[$current_language]['chatbot_placeholder'] ?>">
        <button id="chatbot-send"><?= $translations[$current_language]['chatbot_send'] ?></button>
    </div>
</div>

<!-- Кнопка открытия чата (остается на месте) -->
<button id="chatbot-toggle">💬</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chat = document.getElementById('chatbot-container');
    const chatHeader = document.getElementById('chatbot-header');
    const toggleBtn = document.getElementById('chatbot-toggle');
    const closeBtn = document.getElementById('chatbot-close');
    
    // Переменные для перемещения
    let isDragging = false;
    let offsetX, offsetY;
    
    // Обработчики перемещения
    chatHeader.addEventListener('mousedown', function(e) {
        if (e.target === closeBtn) return;
        
        isDragging = true;
        offsetX = e.clientX - chat.getBoundingClientRect().left;
        offsetY = e.clientY - chat.getBoundingClientRect().top;
        chat.style.cursor = 'grabbing';
    });
    
    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        
        chat.style.left = (e.clientX - offsetX) + 'px';
        chat.style.top = (e.clientY - offsetY) + 'px';
        chat.style.right = 'auto';
        chat.style.bottom = 'auto';
        chat.style.position = 'fixed';
    });
    
    document.addEventListener('mouseup', function() {
        isDragging = false;
        chat.style.cursor = '';
    });
    
    // Открытие/закрытие чата
    toggleBtn.addEventListener('click', function() {
        if (window.getComputedStyle(chat).display === 'none') {
            chat.style.display = 'flex';
            // Сброс позиции при открытии
            chat.style.left = '';
            chat.style.top = '';
            chat.style.right = '20px';
            chat.style.bottom = '80px';
        }
    });
    
    closeBtn.addEventListener('click', function() {
        chat.style.display = 'none';
    });
});
</script>

<script src="script.js"></script>
</body>
</html>