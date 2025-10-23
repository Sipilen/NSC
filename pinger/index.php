<?php
// Настройки
$dataFile = 'ips.json';
$maxEntries = 9;

// Получаем IP-адрес клиента
$clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

// Получаем информацию о стране
$countryInfo = getCountryInfo($clientIP);

// Загружаем существующие данные
$entries = [];
if (file_exists($dataFile)) {
    $entries = json_decode(file_get_contents($dataFile), true) ?? [];
}

// Добавляем новую запись
$entries[] = [
    'ip' => $clientIP,
    'timestamp' => date('Y-m-d H:i:s'),
    'country' => $countryInfo['country'] ?? 'Unknown',
    'flag' => $countryInfo['flag'] ?? '🏴'
];

// Оставляем только последние 9 записей
$entries = array_slice($entries, -$maxEntries);

// Сохраняем данные
file_put_contents($dataFile, json_encode($entries));

// Функция получения информации о стране
function getCountryInfo($ip) {
    if ($ip === 'Unknown' || $ip === '127.0.0.1') {
        return ['country' => 'Local', 'flag' => '🏠'];
    }

    try {
        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        $data = json_decode($response, true);
        
        if ($data['status'] === 'success') {
            return [
                'country' => $data['country'],
                'flag' => getCountryFlag($data['countryCode'])
            ];
        }
    } catch (Exception $e) {
        // Игнорируем ошибки запроса
    }

    return ['country' => 'Unknown', 'flag' => '🏴'];
}

// Функция получения флага по коду страны
function getCountryFlag($countryCode) {
    $flagOffset = 127397;
    $code = strtoupper($countryCode);
    $flag = '';
    
    for ($i = 0; $i < strlen($code); $i++) {
        $flag .= mb_chr(ord($code[$i]) + $flagOffset);
    }
    
    return $flag;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NightSoft Corp - Pinger</title>
    <style>
        body {
            background-color: #0f0f1f;
            color: #e0e0ff;
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #1a1a2f;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #6a0dad;
        }
        
        h1 {
            color: #b19cd9;
            text-align: center;
            text-shadow: 0 0 10px #8a2be2;
        }
        
        .ip-list {
            list-style: none;
            padding: 0;
        }
        
        .ip-entry {
            background: #2d2d4f;
            margin: 10px 0;
            padding: 15px;
            border-left: 4px solid #8a2be2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .ip-address {
            font-weight: bold;
            color: #d8bfd8;
        }
        
        .country {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .flag {
            font-size: 1.5em;
        }
        
        .timestamp {
            color: #a0a0c0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🦇 NightSoftCorp Pinger</h1>
        <div class="current-ip">
            Your IP: <?php echo htmlspecialchars($clientIP); ?>
        </div>
        
        <h2>Айпи:</h2>
        <ul class="ip-list">
            <?php foreach(array_reverse($entries) as $entry): ?>
            <li class="ip-entry">
                <div class="ip-info">
                    <div class="ip-address"><?php echo htmlspecialchars($entry['ip']); ?></div>
                    <div class="timestamp"><?php echo htmlspecialchars($entry['timestamp']); ?></div>
                </div>
                <div class="country">
                    <span class="flag"><?php echo $entry['flag']; ?></span>
                    <span class="country-name"><?php echo htmlspecialchars($entry['country']); ?></span>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
