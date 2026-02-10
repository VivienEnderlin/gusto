<?php
return [

    // 🔐 Clé secrète utilisée pour SIGNER et VÉRIFIER les JWT
    // Elle doit être:
    // - longue
    // - aléatoire
    // - secrète (jamais exposée côté client)
    'JWT_SECRET' => '9c3d8f41b8d4e6d1a7a0e9f93c6e3c5c8d7f4b0f5e8a1c9b0d6f7a8c9e1b2d3',

    // 🔑 Algorithme de signature du token
    // HS256 = HMAC + SHA256 (le plus courant et suffisant)
    'JWT_ALGO'   => 'HS256',

    // ⏱️ Durée de validité du token en secondes
    // Ici : 3600 secondes = 1 heure
    'JWT_EXPIRE' => 3600
];

//echo bin2hex(random_bytes(64)); pour genrer la clé secrete