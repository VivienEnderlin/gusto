<?php
// config/jwt.php
return [
    'JWT_SECRET' => '9c3d8f41b8d4e6d1a7a0e9f93c6e3c5c8d7f4b0f5e8a1c9b0d6f7a8c9e1b2d3',
    'JWT_ALGO'   => 'HS256',
    'JWT_EXPIRE' => 86400 // 24h
];


//echo bin2hex(random_bytes(64)); pour genrer la clé secrete