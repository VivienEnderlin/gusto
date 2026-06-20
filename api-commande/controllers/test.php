<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../utils/phpqrcode/qrlib.php';

header('Content-Type: image/png');

// 🔥 FORCER NETTOYAGE TOTAL
while (ob_get_level()) ob_end_clean();

QRcode::png("TEST HEROKU");

exit;