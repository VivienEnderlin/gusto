<?php

require __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

// return [
//     'JWT_SECRET' => getenv('JWT_SECRET'),
//     'JWT_ALGO'   => getenv('JWT_ALGO'),
//     'JWT_EXPIRE' => getenv('JWT_EXPIRE')
// ];

// ce qui marche en local

return [
    'JWT_SECRET' => $_ENV['JWT_SECRET'],
    'JWT_ALGO'   => $_ENV['JWT_ALGO'],
    'JWT_EXPIRE' => $_ENV['JWT_EXPIRE']
];

//echo bin2hex(random_bytes(64)); pour genrer la clé secrete
