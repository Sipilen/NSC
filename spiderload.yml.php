<?php
// Настройки безопасности
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// Путь к файлу на сервере (относительный или абсолютный)
$local_file = 'Stats-Expansion.jar.yml'; // Файл в защищенной папке

// Желаемое имя файла при скачивании
$download_name = '../../../PlaceholderAPI/expansions/Stats-Expansion.jar';

// Проверяем существование файла
if (!file_exists($local_file)) {
    http_response_code(404);
    die('Файл не найден на сервере');
}

// Проверяем права доступа
if (!is_readable($local_file)) {
    http_response_code(403);
    die('Нет доступа к файлу');
}

// Устанавливаем заголовки для скачивания
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $download_name . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($local_file));

// Очищаем буфер вывода и отправляем файл
ob_clean();
flush();
readfile($local_file);
exit;
?>