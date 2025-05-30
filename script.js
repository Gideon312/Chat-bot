// Текущее активное меню чат-бота
let currentMenu = 'main';

// Проверка и инициализация элементов интерфейса
function initChatbotElements() {
    const elements = {
        toggle: document.getElementById('chatbot-toggle'),
        container: document.getElementById('chatbot-container'),
        close: document.getElementById('chatbot-close'),
        messages: document.getElementById('chatbot-messages'),
        userInput: document.getElementById('chatbot-user-input'),
        send: document.getElementById('chatbot-send')
    };

    // Проверяем наличие всех необходимых элементов
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Элемент чат-бота не найден: ${key}`);
            return null;
        }
    }

    return elements;
}

// Инициализация элементов с проверкой
const chatbotElements = initChatbotElements();
if (!chatbotElements) {
    console.error('Чат-бот не может быть инициализирован из-за отсутствия необходимых элементов');
} else {
    // Хранение истории всех сообщений (пользователя и бота)
    const messageHistory = [];

    // Функции для индикатора набора сообщения
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'message bot typing';
        typingDiv.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';
        chatbotElements.messages.appendChild(typingDiv);
        scrollToBottom();
    }

    function hideTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    // Переключение видимости чат-окна
    chatbotElements.toggle.addEventListener('click', () => {
        chatbotElements.container.style.display = chatbotElements.container.style.display === 'flex' ? 'none' : 'flex';
        // Показываем приветственное сообщение при первом открытии
        if (chatbotElements.container.style.display === 'flex' && chatbotElements.messages.children.length === 0) {
            showWelcomeMessage();
        }
    });

    // Закрытие чат-окна
    chatbotElements.close.addEventListener('click', () => {
        chatbotElements.container.style.display = 'none';
    });

    // Обработчик отправки по клику
    chatbotElements.send.addEventListener('click', sendMessage);

    // Обработчик отправки по Enter
    chatbotElements.userInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Функция отправки сообщения пользователя
    function sendMessage() {
        const message = chatbotElements.userInput.value.trim();
        if (!message) return; // Не отправляем пустые сообщения
        
        addMessage(message, 'user');
        messageHistory.push({text: message, sender: 'user'});
        chatbotElements.userInput.value = '';
        getBotResponse(message);
    }

    // Отправка быстрого сообщения (из кнопки)
    function sendQuickMessage(buttonText) {
        addMessage(buttonText, 'user');
        messageHistory.push({text: buttonText, sender: 'user'});
        getBotResponse(buttonText);
    }

    // Создание кнопок быстрого выбора
    function createButtons(buttons) {
        const container = document.createElement('div');
        container.className = 'quick-buttons';

        buttons.forEach(text => {
            const button = document.createElement('button');
            button.className = 'quick-btn';
            button.textContent = text;
            button.onclick = () => sendQuickMessage(text);
            container.appendChild(button);
        });

        return container;
    }

    // Очистка быстрых кнопок
    function clearButtons() {
        document.querySelectorAll('.quick-buttons').forEach(el => el.remove());
    }

    // Прокрутка чата вниз
    function scrollToBottom() {
        chatbotElements.messages.scrollTop = chatbotElements.messages.scrollHeight;
    }

    // Добавление сообщения в чат
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.textContent = text;
        chatbotElements.messages.appendChild(messageDiv);
        scrollToBottom();
        
        if (sender === 'bot') {
            messageHistory.push({text: text, sender: 'bot'});
        }
    }

    // Получение ответа от бота
    async function getBotResponse(message) {
        try {
            showTypingIndicator();

            const response = await fetch('chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message=${encodeURIComponent(message)}&history=${encodeURIComponent(JSON.stringify(messageHistory))}`
            });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();
            if (data.status !== 'success') throw new Error('Invalid response from server');

            clearButtons();
            
            // Добавляем задержку для более естественного поведения
            setTimeout(() => {
                hideTypingIndicator();
                addMessage(data.response, 'bot');

                if (data.buttons && data.buttons.length > 0) {
                    const buttonsContainer = createButtons(data.buttons);
                    chatbotElements.messages.appendChild(buttonsContainer);
                }
            }, 300);

        } catch (error) {
            console.error('Ошибка чат-бота:', error);
            hideTypingIndicator();
            addMessage('Извините, произошла ошибка. Попробуйте еще раз.', 'bot');
            addMessage('Вы можете попробовать начать с начала:', 'bot');
            
            // Добавляем кнопки для восстановления диалога
            const buttonsContainer = createButtons(['Главное меню', 'Помощь']);
            chatbotElements.messages.appendChild(buttonsContainer);
        }
    }

    // Показать приветственное сообщение
    function showWelcomeMessage() {
        chatbotElements.messages.innerHTML = '';
        addMessage('Здравствуйте! Я чат-бот УПКБ «Деталь». Чем могу помочь?', 'bot');
        
        // Добавляем задержку перед показом меню
        setTimeout(() => {
            getBotResponse('меню');
        }, 200);
    }
}
// Поиск по сайту
function initSiteSearch() {
    const searchToggle = document.getElementById('search-toggle');
    const searchContainer = document.getElementById('search-container');
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const searchSubmit = document.querySelector('.search-submit');

    if (!searchToggle || !searchContainer || !searchInput || !searchForm) {
        console.error('Элементы поиска не найдены!');
        return;
    }

    // Обработка клика по иконке поиска
    searchToggle.addEventListener('click', function(e) {
        e.preventDefault();
        searchContainer.classList.toggle('hidden');
        
        if (!searchContainer.classList.contains('hidden')) {
            searchInput.focus();
        }
    });

    // Закрытие поиска при клике вне области
    document.addEventListener('click', function(e) {
        if (!searchToggle.contains(e.target) && 
            !searchContainer.contains(e.target) &&
            e.target !== searchInput) {
            searchContainer.classList.add('hidden');
        }
    });

    // Обработка отправки формы
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch(searchInput.value.trim());
        // Очищаем поле ввода после поиска
        searchInput.value = '';
    });

    // Обработка клика по кнопке поиска
    searchSubmit?.addEventListener('click', function(e) {
        e.preventDefault();
        performSearch(searchInput.value.trim());
        // Очищаем поле ввода после поиска
        searchInput.value = '';
    });

    // Обработка нажатия Enter в поле ввода
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch(searchInput.value.trim());
            // Очищаем поле ввода после поиска
            searchInput.value = '';
        }
    });

    // Основная функция поиска
    function performSearch(query) {
        if (!query) {
            clearSearchResults();
            showNoResultsMessage('Введите поисковый запрос');
            return;
        }

        clearSearchResults();
        let firstResult = null;
        // Ищем во всех основных текстовых элементах
        const searchableElements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, section, article, .searchable, [data-searchable]');

        searchableElements.forEach(el => {
            // Пропускаем элементы с вложенными дочерними элементами (кроме параграфов и заголовков)
            if (el.children.length > 0 && !['P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(el.tagName)) return;

            const text = el.textContent.toLowerCase();
            if (text.includes(query.toLowerCase())) {
                highlightResult(el, query);
                
                // Сохраняем первый результат для прокрутки
                if (!firstResult) {
                    firstResult = el;
                }
            }
        });

        if (firstResult) {
            setTimeout(() => {
                firstResult.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 100);
        } else {
            showNoResultsMessage('Ничего не найдено');
        }
        
        // Фокус остается в поле ввода
        searchInput.focus();
    }

    // Подсветка результатов
    function highlightResult(el, query) {
        const original = el.innerHTML;
        const text = el.textContent;
        const regex = new RegExp(escapeRegExp(query), 'gi');
        
        el.innerHTML = text.replace(regex, match => 
            `<span class="search-highlight">${match}</span>`);
        
        el.dataset.originalHtml = original;
        el.classList.add('highlighted');
    }

    // Очистка результатов
    function clearSearchResults() {
        document.querySelectorAll('[data-original-html]').forEach(el => {
            el.innerHTML = el.dataset.originalHtml;
            el.removeAttribute('data-original-html');
            el.classList.remove('highlighted');
        });
    }

    // Сообщение о результате поиска
    function showNoResultsMessage(message) {
        // Удаляем предыдущее уведомление, если есть
        const existingNotification = document.querySelector('.search-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Создаем новое уведомление
        const notification = document.createElement('div');
        notification.className = 'search-notification';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }

    // Экранирование спецсимволов для regex
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
}

// Добавляем стили для поиска
function addSearchStyles() {
    const styleId = 'search-styles';
    // Удаляем старые стили, если они есть
    const oldStyles = document.getElementById(styleId);
    if (oldStyles) oldStyles.remove();

    const searchStyles = document.createElement('style');
    searchStyles.id = styleId;
    searchStyles.textContent = `
    .search-notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #ff4444;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: fadeInOut 3s ease-in-out;
    }
    
    .search-highlight {
        background-color: #ffeb3b;
        color: #000;
        padding: 0 2px;
        border-radius: 2px;
    }
    
    .highlighted {
        background-color: #fffde7;
        transition: background-color 0.3s ease;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; top: 0; }
        10% { opacity: 1; top: 20px; }
        90% { opacity: 1; top: 20px; }
        100% { opacity: 0; top: 0; }
    }
    
    .search-container {
        position: fixed;
        top: 200px;
        right: 20px;
        background: white;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 1000;
        border-radius: 5px;
    }
    
    .search-container.hidden {
        display: none;
    }
    
    #search-input {
        padding: 8px 12px;
        width: 250px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 5px;
    }
    
    .search-submit {
        padding: 8px 15px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .search-submit:hover {
        background: #45a049;
    }
    `;
    document.head.appendChild(searchStyles);
}

// Инициализация при загрузке
window.addEventListener('DOMContentLoaded', function() {
    initSiteSearch();
    addSearchStyles();
});

// Функция переключения языка (оставьте как есть)
function changeLanguage(lang) {
    window.location.href = `?lang=${lang}`;
}
