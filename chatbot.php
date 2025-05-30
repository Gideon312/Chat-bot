<?php
header('Content-Type: application/json');
session_start();

// Инициализация сессии для хранения истории чата
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

// Функция для получения последних новостей
function getLatestNews() {
    return "📰 Последние новости:\n\n"
         . "• Более 1 млрд рублей Ростех инвестирует в передовые инженерные школы в текущем году.\n"
         . "Организации Ростеха в 2025 году профинансируют передовые инженерные школы (ПИШ) более чем на 1 млрд рублей.\n\n"
         . "• Завершены работы по разделке кормовой части танкера «Волгонефть-239»\n"
         . "• В российских городах-миллионниках 9 мая откроют парки и проведут парады";
}

// Основные данные для ответов
$menuData = [
    'main' => [
        'response' => "Главное меню:",
        'buttons' => ['О предприятии', 'История', 'Производство', 'Продукция', 'Последние новости', 'Контакты']
    ],
    'about' => [
        'response' => "🏭 Уральское проектно-конструкторское бюро \"Деталь\" является ведущим предприятием России по разработке и производству радиовысотомеров и радиовысотомерных систем для авиационной и космической техники.",
        'buttons' => ['История', 'Производство', 'Продукция', 'Контакты', 'Назад в меню']
    ],
    'history' => [
        'response' => "📜 Предприятие основано в 1949 году. Выберите интересующий период:",
        'buttons' => ['1940-1950', '1960-1970', '1980-1990', '2000-2010', '2011-2025', 'Ключевые направления','Назад в меню']
    ],
    'history_1940' => [
        'response' => "1940-1950-е: Основание и первые разработки\n\n"
                    . "10 сентября 1949 года\n"
                    . "ОАО \"Уральское проектно-конструкторское бюро \"Деталь\", первоначально называвшееся \"ОКБ-379\", создано в системе Министерства авиационной промышленности как головная организация по разработке радиовысотомеров для всех типов летательных аппаратов.\n\n"
                    . "1956 год\n"
                    . "Начальником УПКБ – главным конструктором назначен Владимир Семенович Фомин, который был бессменным руководителем предприятия в течение 32 лет.\n\n"
                    . "1957 год\n"
                    . "Была завершена разработка и освоен в серийном производстве первый радиовысотомер малых высот \"Уралец\".\n\n"
                    . "1959 год\n"
                    . "После успешных испытаний начались поставки первого радиовысотомера больших высот РВ-25 для нужд Вооружённых Сил.",
        'buttons' => ['1960-1970', '1980-1990', '2000-2010', '2010-2025', 'Ключевые направления', 'Назад в меню']
    ],
    'history_1960' => [
        'response' => "1960-1970-е: Космические достижения\n\n"
                    . "20 сентября 1970 года\n"
                    . "С помощью радиовысотомеров предприятия впервые в мировой практике осуществлена мягкая посадка космического аппарата на Луну.\n\n"
                    . "1969 год\n"
                    . "За разработку и внедрение новой техники большая группа работников предприятия награждена высокими правительственными наградами, а В.М. Жукову присвоено звание лауреата Государственной премии.\n\n"
                    . "Середина 70-х годов\n"
                    . "Аналитики бюро провели исследовательские работы, направленные на оценку рынка авиации и потребности в радиовысотомерах.",
        'buttons' => ['1940-1950', '1980-1990', '2000-2010', '2010-2025', 'Ключевые направления', 'Назад в меню']
    ],
    'history_1980' => [
        'response' => "1980-1990-е: Цифровая революция\n\n"
                    . "1988 год\n"
                    . "Завершена разработка первого самолетного радиовысотомера малых высот РВ-85 с применением микропроцессоров и цифровой обработки сигнала.\n\n"
                    . "1992 год\n"
                    . "По заданию французской фирмы EHS УПКБ \"Деталь\" выполнило разработку технического предложения по оснащению радиовысотомером для автоматической посадки многоразового космического корабля \"Гермес\" Европейского космического агентства.\n\n"
                    . "1998 год\n"
                    . "Созданы малогабаритные радиовысотомеры А-052 и А-053 для перспективных летательных аппаратов.",
        'buttons' => ['1940-1950', '1960-1970', '2000-2010', '2010-2025', 'Ключевые направления', 'Назад в меню']
    ],
    'history_2000' => [
        'response' => "2000-2010-е: Международное признание\n\n"
                    . "В 2000 году\n"
                    . "УПКБ \"Деталь\" стало победителем Всероссийского конкурса \"100 лучших предприятий и организаций машиностроения России XXI века\".\n\n"
                    . "24 января 2002 года\n"
                    . "Подписан Указ Президента РФ о создании ОАО \"Корпорация \"Тактическое ракетное вооружение\", в которое вошло УПКБ \"Деталь\".\n\n"
                    . "В 2007 году\n"
                    . "Получено Свидетельство Авиационного регистра Международного авиационного комитета об одобрении производства и разрешении производства на ОАО \"УПКБ \"Деталь\" авиационных радиовысотомеров А-053-07, А-053-08 и их модификаций.",
        'buttons' => ['1940-1950', '1960-1970', '1980-1990', '2011-2025', 'Ключевые направления', 'Назад в меню']
    ],
    'history_2011' => [
        'response' => "2011-2025: Современные технологии\n\n"
                    . "2012-2015 годы\n"
                    . "Разработка нового поколения радиовысотомеров с фазированными антенными решетками для перспективных авиационных комплексов 5-го поколения.\n\n"
                    . "2018 год\n"
                    . "Запуск программы импортозамещения электронных компонентов в ответ на санкционное давление.\n\n"
                    . "2020 год\n"
                    . "Участие в создании систем для беспилотных летательных аппаратов нового поколения.\n\n"
                    . "2022-2023 годы\n"
                    . "Адаптация производства к повышенным оборонным заказам, модернизация производственных линий.\n\n"
                    . "2024-2025 годы\n"
                    . "Разработка радиовысотомеров с элементами искусственного интеллекта для автономной работы в сложных условиях.",
        'buttons' => ['1940-1950', '1960-1970', '1980-1990', '2000-2010', 'Ключевые направления', 'Назад в меню']
    ],
    'history_key' => [
        'response' => "Ключевые направления деятельности в 2025 году:\n\n"
                    . "• Разработка и производство радиовысотомеров для авиации и космической техники\n"
                    . "• Создание систем для беспилотных летательных аппаратов\n"
                    . "• Участие в программе импортозамещения электронных компонентов\n"
                    . "• Разработка интеллектуальных систем навигации и посадки",
        'buttons' => ['1940-1950', '1960-1970', '1980-1990', '2000-2010', '2010-2025', 'Назад в меню']
    ],
    'production' => [
        'response' => "🔧 На предприятии организован замкнутый цикл изготовления изделий – от заготовки до конечной продукции, включая этапы контроля, испытаний и сдачи изделий заказчику. Имеющаяся материально-техническая база, находящаяся на завершающих этапах модернизации и технического перевооружения, позволяет изготавливать продукцию собственными силами, без привлечения сторонних технологических переделов.",
        'buttons' => ['О предприятии', 'Продукция', 'Контакты', 'Назад в меню']
    ],
    'products' => [
        'response' => "🛠️ Выберите категорию продукции:",
        'buttons' => [
            'Радиовысотомеры малых высот',
            'Радиовысотомеры средних и больших высот',
            'Бортовые радиолокационные станции',
            'Разработки',
            'Назад в меню'
        ]
    ],
    'product_small' => [
        'response' => "🔹 Радиовысотомеры малых высот:\n\nВыберите конкретную модель:",
        'buttons' => [
            'А-065А',
            'А-037',
            'А-052',
            'А-053',
            'Назад к продукции',
            'Назад в меню'
        ]
    ],
    'product_medium' => [
        'response' => "🔹 Радиовысотомеры средних/больших высот:\n\nВыберите конкретную модель:",
        'buttons' => [
            'А-075-02',
            'А-075-05',
            'А-076М',
            'А-098',
            'Назад к продукции',
            'Назад в меню'
        ]
    ],
    'product_radar' => [
        'response' => "🔹 Бортовые радиолокационные станции:\n\nВыберите конкретную модель:",
        'buttons' => [
            'Овод',
            'Филин', 
            'Шмель-М',
            'Назад к продукции',
            'Назад в меню'
        ]
    ],
    'product_special' => [
        'response' => "🔹 Специальные разработки и измерительные системы:\n\nВыберите конкретную модель:",
        'buttons' => [
            'ИКБО "АВИАТОР"',
            'Малогабаритный А-098',
            'Имитатор ИОС-РВ',
            'А-053М',
            'А-052-28М',
            'РВ-ИМА',
            'Назад к продукции',
            'Назад в меню'
        ]
    ],
    'product_special_aviator' => [
        'response' => "🔹 ИКБО «АВИАТОР» - АВИОНИКА ДЛЯ ВОЗДУШНЫХ СУДОВ НАШЕГО ВРЕМЕНИ.\n\nИсполнение интегрированных комплексов бортового оборудования, разработанных для различных типов воздушных судов местных воздушных линий. Наши решения сочетают классический подход технологий, универсальность и надежность, обеспечивая безупречную работу экипажа и управление полетом.",
        'buttons' => ['Разработки', 'Назад к продукции', 'Назад в меню']
    ],
    'product_special_a098' => [
        'response' => "🔹 Радиовысотомер А-098 (малогабаритный):\n\nРадиовысотомер А-098 является бортовой радиолокационной станцией с импульсным излучением радиоволн. Он имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации. Предназначен для пилотируемых летательных аппаратов, но может быть установлен на любой тип летательных аппаратов.",
        'buttons' => ['Разработки', 'Назад к продукции', 'Назад в меню']
    ],
    'product_special_ios' => [
        'response' => "🔹 Имитатор отражённых сигналов (ИОС-РВ):\n\nИмитатор отражённых сигналов (ИОС) предназначен для имитации временной задержки и ослабления излученного СВЧ сигнала радиовысотомера в соответствии с заданными параметрами антенной системы, высотой полёта, типом подстилающей поверхности, углами крена и тангажа, скоростью летательного аппарата. Может применяться в комплексах полунатурного моделирования систем БРЭО летательного аппарата.",
        'buttons' => ['Разработки', 'Назад к продукции', 'Назад в меню']
    ],
    'product_special_a053m' => [
        'response' => "🔹 Радиовысотомер А-053М:\n\nРадиовысотомер А-053М является бортовой радиолокационной станцией с непрерывным излучением частотно-модулированных радиоволн. Имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации. Конструкция приемопередатчика позволяет установку без амортизации на борт летательного аппарата.",
        'buttons' => ['Разработки', 'Назад к продукции', 'Назад в меню']
    ],
    'product_special_a05228m' => [
        'response' => "🔹 Радиовысотомер А-052-28М (специальная разработка):\n\nРадиовысотомер А-052-28М является бортовой радиолокационной станцией с непрерывным излучением частотно-модулированных волн. Имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации.",
        'buttons' => ['Разработки', 'Назад к продукции', 'Назад в меню']
    ],
    'product_special_rvima' => [
        'response' => "🔹 Радиовысотомер РВ-ИМА (Траверз-К):\n\nРадиовысотомер РВ-ИМА (Траверз-К) является бортовой радиолокационной станцией с непрерывным излучением частотно-модулированных радилволн. Имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации. Конструкция приемопередатчика позволяет установку без амортизации на борт летательного аппарата. Предназначен для применения на БПЛА, легкомоторной авиации, может быть установлен на авиации общего назначения.",
        'buttons' => ['Разработки', 'Назад к продукции', 'Назад в меню']
    ],
    'product_ovod' => [
        'response' => "🔹 Малогабаритная радиовысотомерная система \"Овод\":\n\nСистема с функций измерения высот, составляющих вектора скорости предназначена для применения на малоскоростных летательных аппаратах (вертолет).",
        'buttons' => ['Бортовые РЛС', 'Назад к продукции', 'Назад в меню']
    ],
    'product_filin' => [
        'response' => "🔹 Многофункциональная РЛС переднего и бокового обзора \"Филин\":\n\nИспользуется на всех этапах полетного задания при взаимодействии с КБО носителя, при ведении мониторинга и поисково-спасательных работ, общих навигационных задач, в том числе задач посадки, днем и ночью в простых и сложных метеоусловиях.",
        'buttons' => ['Бортовые РЛС', 'Назад к продукции', 'Назад в меню']
    ],
    'product_shmel' => [
        'response' => "🔹 Радиолокационная станция бокового обзора \"Шмель-М\":\n\nЭкспериментальный образец радиолокационной станции бокового обзора c синтезированной апертурой для дистанционного зондирования земной поверхности.",
        'buttons' => ['Бортовые РЛС', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-065A' => [
        'response' => "🔹 Радиовысотомер А-065А:\n\nЯвляется бортовой радиолокационной станцией с импульсным излучением радиоволн. Он имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации. Предназначен для дистанционно-пилотируемых летательных аппаратов, но может быть установлен на любой тип летательного аппарата.",
        'buttons' => ['Радиовысотомеры малых высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-037' => [
        'response' => "🔹 Радиовысотомер А-037:\n\nЯвляется бортовой радиолокационной станцией с непрерывным излучением частотно-модулированных радиоволн. А-037 не имеет ступенчатой ошибки, соответствует требованиям TSO-C87. Лёгкий и компактный, точный и надёжный, удобный в эксплуатации.",
        'buttons' => ['Радиовысотомеры малых высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-052' => [
        'response' => "🔹 Радиовысотомер А-052:\n\nЯвляется бортовой радиолокационной станцией с непрерывным излучением частотно-модулированных радиоволн. Имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации.",
        'buttons' => ['Радиовысотомеры малых высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-053' => [
        'response' => "🔹 Радиовысотомер А-053:\n\nЯвляется бортовой радиолокационной станцией с непрерывным излучением частотно-модулированных волн. Он имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации.",
        'buttons' => ['Радиовысотомеры малых высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-075-02' => [
        'response' => "🔹 Радиовысотомер А-075-02:\n\nСовмещает функции двух радиовысотомеров - малых и больших высот, предназначен для работы в составе пилотажно-навигационных комплексов самолетов скоростной авиации. По желанию заказчика радиовысотомер может выдавать по каналу последовательного кода признак перехода суша-море, среднюю высоту морских волн.",
        'buttons' => ['Радиовысотомеры средних и больших высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-075-05' => [
        'response' => "🔹 Радиовысотомер А-075-05:\n\nИмпульсный радиовысотомер больших высот, предназначен для измерения высоты полёта различных типов самолётов.",
        'buttons' => ['Радиовысотомеры средних и больших высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-076M' => [
        'response' => "🔹 Радиовысотомер А-076М:\n\nРадиолокационный комплексный измеритель высоты полета и составляющих вектора путевой скорости для объектов гражданской авиации. Комплексный измеритель объединяет функции импульсного радиовысотомера и корреляционного измерителя составляющих путевой скорости, предназначен для объектов гражданской авиации, работоспособен над всеми видами поверхности.",
        'buttons' => ['Радиовысотомеры средних и больших высот', 'Назад к продукции', 'Назад в меню']
    ],
    'product_A-098' => [
        'response' => "🔹 Радиовысотомер А-098:\n\nЯвляется бортовой радиолокационной станцией с импульсным излучением радиоволн. Он имеет малые габариты и массу, высокую надежность и достоверность выдаваемой информации. Предназначен для пилотируемых летательных аппаратов, но может быть установлен на любой тип летательных аппаратов.",
        'buttons' => ['Радиовысотомеры средних и больших высот', 'Назад к продукции', 'Назад в меню']
    ],
    'news' => [
        'response' => getLatestNews(),
        'buttons' => ['О предприятии', 'Продукция', 'Контакты', 'Назад в меню']
    ],
    'contacts' => [
        'response' => "📞 Контактная информация\n\n"
            . "📍 Адрес: Россия, 623409, г. Каменск-Уральский Свердловской области, ул. Пионерская, 8\n\n"
            . "📱 Отдел кадров: +7 3439 33-92-16\n\n"
            . "👔 Начальник управления по работе с персоналом: +7 (3439) 37-58-54\n\n"
            . "✉️ E-Mail: upkb@upkb.ru, upkb@nexcom.ru",       
        'buttons' => ['О предприятии', 'Производство', 'Продукция', 'Назад в меню']
    ],
    'greeting' => [
        'response' => "Здравствуйте! Я чат-бот УПКБ «Деталь». Чем могу помочь? Вот что я умею:\n\n"
                    . "• Рассказать о нашем предприятии\n"
                    . "• Показать историю компании\n"
                    . "• Описать наше производство\n"
                    . "• Показать нашу продукцию\n"
                    . "• Поделиться последними новостями\n"
                    . "• Дать контактную информацию\n\n"
                    . "Выберите интересующий вас раздел или задайте вопрос.",
        'buttons' => ['О предприятии', 'История', 'Производство', 'Продукция', 'Последние новости', 'Контакты']
    ],
    'help' => [
        'response' => "Я могу ответить на вопросы о нашем предприятии также рассказать о продукции. Вот основные команды:\n\n"
                    . "• О предприятии - информация о компании\n"
                    . "• История - исторические периоды развития\n"
                    . "• Производство - о нашем производстве\n"
                    . "• Продукция - наш ассортимент\n"
                    . "• Новости - последние новости\n"
                    . "• Контакты - как с нами связаться\n"
                    . "• Помощь - это сообщение\n\n"
                    . "Вы также можете просто написать свой вопрос.",
        'buttons' => ['О предприятии', 'История', 'Производство', 'Продукция', 'Последние новости', 'Контакты']
    ],
    'unknown' => [
        'response' => "Извините, я не совсем понял ваш вопрос. Попробуйте переформулировать или выберите один из вариантов:",
        'buttons' => ['О предприятии', 'История', 'Производство', 'Продукция', 'Последние новости', 'Контакты']
    ]
];

// Получаем сообщение от пользователя
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Добавляем сообщение пользователя в историю чата
if (!empty($message)) {
    $_SESSION['chat_history'][] = [
        'sender' => 'user',
        'message' => $message,
        'time' => date('H:i')
    ];
}

// Обработка сообщений
$response = $menuData['main']['response'];
$buttons = $menuData['main']['buttons'];


// Расширенный список фраз для обработки
if (str_contains(mb_strtolower($message), 'о предприятии') || str_contains(mb_strtolower($message), 'предприяти') 
    || str_contains(mb_strtolower($message), 'кто вы') || str_contains(mb_strtolower($message), 'чем занимаетесь')
    || str_contains(mb_strtolower($message), 'ваша компания') || str_contains(mb_strtolower($message), 'информация о компании')) {
    $response = $menuData['about']['response'];
    $buttons = $menuData['about']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'истори') || str_contains(mb_strtolower($message), 'когда основан')
    || str_contains(mb_strtolower($message), 'основание') || str_contains(mb_strtolower($message), 'развитие компании')) {
    $response = $menuData['history']['response'];
    $buttons = $menuData['history']['buttons'];
} elseif (str_contains(mb_strtolower($message), '1940-1950') || str_contains(mb_strtolower($message), '1940') || str_contains(mb_strtolower($message), '1950')) {
    $response = $menuData['history_1940']['response'];
    $buttons = $menuData['history_1940']['buttons'];
} elseif (str_contains(mb_strtolower($message), '1960-1970') || str_contains(mb_strtolower($message), '1960') || str_contains(mb_strtolower($message), '1970')) {
    $response = $menuData['history_1960']['response'];
    $buttons = $menuData['history_1960']['buttons'];
} elseif (str_contains(mb_strtolower($message), '1980-1990') || str_contains(mb_strtolower($message), '1980') || str_contains(mb_strtolower($message), '1990')) {
    $response = $menuData['history_1980']['response'];
    $buttons = $menuData['history_1980']['buttons'];
} elseif (str_contains(mb_strtolower($message), '2000-2010') || str_contains(mb_strtolower($message), '2000') || str_contains(mb_strtolower($message), '2010')) {
    $response = $menuData['history_2000']['response'];
    $buttons = $menuData['history_2000']['buttons'];
} elseif (str_contains(mb_strtolower($message), '2011-2025') || str_contains(mb_strtolower($message), '2010') || str_contains(mb_strtolower($message), '2025')) {
    $response = $menuData['history_2011']['response'];
    $buttons = $menuData['history_2011']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'ключевые направления') || str_contains(mb_strtolower($message), 'направления') || str_contains(mb_strtolower($message), 'ключевые')) {
    $response = $menuData['history_key']['response'];
    $buttons = $menuData['history_key']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'производств') || str_contains(mb_strtolower($message), 'производст')) {
    $response = $menuData['production']['response'];
    $buttons = $menuData['production']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'продукц') || str_contains(mb_strtolower($message), 'товар') || str_contains(mb_strtolower($message), 'ассортимент')) {
    $response = $menuData['products']['response'];
    $buttons = $menuData['products']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'помощь') || str_contains(mb_strtolower($message), 'команды')
    || str_contains(mb_strtolower($message), 'что ты умееш') || str_contains(mb_strtolower($message), 'функции')) {
    $response = $menuData['help']['response'];
    $buttons = $menuData['help']['buttons'];
} elseif (empty($message) || str_contains(mb_strtolower($message), 'привет') 
    || str_contains(mb_strtolower($message), 'здравств') || str_contains(mb_strtolower($message), 'начать')
    || str_contains(mb_strtolower($message), 'старт')) {
    $response = $menuData['greeting']['response'];
    $buttons = $menuData['greeting']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'малых высот') || str_contains(mb_strtolower($message), 'назад к продукции') || str_contains(mb_strtolower($message), 'малые высоты')) {
    $response = $menuData['product_small']['response'];
    $buttons = $menuData['product_small']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'средних и больших высот') || str_contains(mb_strtolower($message), 'средние высоты') || str_contains(mb_strtolower($message), 'большие высоты')) {
    $response = $menuData['product_medium']['response'];
    $buttons = $menuData['product_medium']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-065а') || str_contains(mb_strtolower($message), 'а065а') || str_contains(mb_strtolower($message), 'а 065а')) {
    $response = $menuData['product_A-065A']['response'];
    $buttons = $menuData['product_A-065A']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-037') || str_contains(mb_strtolower($message), 'а037') || str_contains(mb_strtolower($message), 'а 037')) {
    $response = $menuData['product_A-037']['response'];
    $buttons = $menuData['product_A-037']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'измерительные системы') || str_contains(mb_strtolower($message), 'разработки') || str_contains(mb_strtolower($message), 'специальные')) {
    $response = $menuData['product_special']['response'];
    $buttons = $menuData['product_special']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-052-28м') || str_contains(mb_strtolower($message), 'а05228м') || (str_contains(mb_strtolower($message), 'а-052') && str_contains(mb_strtolower($message), '28м'))) {
    $response = $menuData['product_special_a05228m']['response'];
    $buttons = $menuData['product_special_a05228m']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'малогабаритный а-098') || (str_contains(mb_strtolower($message), 'а-098') && str_contains(mb_strtolower($message), 'малогабаритный'))) {
    $response = $menuData['product_special_a098']['response'];
    $buttons = $menuData['product_special_a098']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'икбо') || str_contains(mb_strtolower($message), 'авиатор')) {
    $response = $menuData['product_special_aviator']['response'];
    $buttons = $menuData['product_special_aviator']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'имитатор') || str_contains(mb_strtolower($message), 'иос-рв')) {
    $response = $menuData['product_special_ios']['response'];
    $buttons = $menuData['product_special_ios']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-053м') || str_contains(mb_strtolower($message), 'а053м')) {
    $response = $menuData['product_special_a053m']['response'];
    $buttons = $menuData['product_special_a053m']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'рв-има') || str_contains(mb_strtolower($message), 'рвима') || str_contains(mb_strtolower($message), 'траверз-к')) {
    $response = $menuData['product_special_rvima']['response'];
    $buttons = $menuData['product_special_rvima']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-052') || str_contains(mb_strtolower($message), 'а052') || str_contains(mb_strtolower($message), 'а 052')) {
    $response = $menuData['product_A-052']['response'];
    $buttons = $menuData['product_A-052']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-053') || str_contains(mb_strtolower($message), 'а053') || str_contains(mb_strtolower($message), 'а 053')) {
    $response = $menuData['product_A-053']['response'];
    $buttons = $menuData['product_A-053']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-075-02') || str_contains(mb_strtolower($message), 'а07502') || str_contains(mb_strtolower($message), 'а 075 02')) {
    $response = $menuData['product_A-075-02']['response'];
    $buttons = $menuData['product_A-075-02']['response'];
} elseif (str_contains(mb_strtolower($message), 'а-075-05') || str_contains(mb_strtolower($message), 'а07505') || str_contains(mb_strtolower($message), 'а 075 05')) {
    $response = $menuData['product_A-075-05']['response'];
    $buttons = $menuData['product_A-075-05']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-076м') || str_contains(mb_strtolower($message), 'а076м') || str_contains(mb_strtolower($message), 'а 076м')) {
    $response = $menuData['product_A-076M']['response'];
    $buttons = $menuData['product_A-076M']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'а-098') || str_contains(mb_strtolower($message), 'а098') || str_contains(mb_strtolower($message), 'а 098')) {
    $response = $menuData['product_A-098']['response'];
    $buttons = $menuData['product_A-098']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'бортовые рлс') || str_contains(mb_strtolower($message), 'бортовая рлс') || str_contains(mb_strtolower($message), 'рлс') || str_contains(mb_strtolower($message), 'радиолокационные') || str_contains(mb_strtolower($message), 'радиолокационн')) {
    $response = $menuData['product_radar']['response'];
    $buttons = $menuData['product_radar']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'овод')) {
    $response = $menuData['product_ovod']['response'];
    $buttons = $menuData['product_ovod']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'филин')) {
    $response = $menuData['product_filin']['response'];
    $buttons = $menuData['product_filin']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'шмель') || str_contains(mb_strtolower($message), 'шмель-м')) {
    $response = $menuData['product_shmel']['response'];
    $buttons = $menuData['product_shmel']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'новост') || str_contains(mb_strtolower($message), 'новости') || str_contains(mb_strtolower($message), 'новостей')) {
    $response = $menuData['news']['response'];
    $buttons = $menuData['news']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'контакт') || str_contains(mb_strtolower($message), 'контакты') || str_contains(mb_strtolower($message), 'адрес') || str_contains(mb_strtolower($message), 'телефон')) {
    $response = $menuData['contacts']['response'];
    $buttons = $menuData['contacts']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'назад') || str_contains(mb_strtolower($message), 'главное меню') || str_contains(mb_strtolower($message), 'меню')) {
    $response = $menuData['main']['response'];
    $buttons = $menuData['main']['buttons'];
} elseif (str_contains(mb_strtolower($message), 'привет') || str_contains(mb_strtolower($message), 'здравств') || str_contains(mb_strtolower($message), 'начать')) {
    $response = $menuData['greeting']['response'];
    $buttons = $menuData['greeting']['buttons'];
} else {
    $response = $menuData['unknown']['response'];
    $buttons = $menuData['unknown']['buttons'];
}

// Добавляем ответ бота в историю чата
$_SESSION['chat_history'][] = [
    'sender' => 'bot',
    'message' => $response,
    'time' => date('H:i')
];

// Возвращаем ответ в формате JSON
echo json_encode([
    'response' => $response,
    'buttons' => $buttons,
    'chat_history' => $_SESSION['chat_history'],  // Возвращаем историю чата
    'status' => 'success'
], JSON_UNESCAPED_UNICODE);
?>