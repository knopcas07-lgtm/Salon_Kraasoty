<?php
// config/config.php — константы приложения

// Корневой путь проекта (один уровень выше public_html/)
define('ROOT_PATH', dirname(__DIR__));

define('BASE_URL', 'http://n91378xg.beget.tech');

// Путь до папки uploads (внутри public_html)
define('UPLOAD_DIR', ROOT_PATH . '/public_html/uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');

// Допустимые MIME-типы для загружаемых изображений
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Временные слоты записи
define('TIME_SLOTS', ['12:00', '14:30', '17:00', '19:30']);

// Статусы записей
define('STATUS_PENDING',   'pending');
define('STATUS_CONFIRMED', 'confirmed');
define('STATUS_COMPLETED', 'completed');
define('STATUS_CANCELLED', 'cancelled');

// Роли пользователей
define('ROLE_ADMIN',  'admin');
define('ROLE_CLIENT', 'client');