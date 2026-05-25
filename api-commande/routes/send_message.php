<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Charger .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();

$nom = $_POST['nom'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$message = $_POST['message'] ?? '';

$token = $_ENV['TELEGRAM_TOKEN'];

$chat_id = $_ENV['ID'];

$text = "
📩 Nouveau message Gusto

👤 Nom : $nom

📞 Téléphone : $telephone

💬 Message :
$message
";

$url = "https://api.telegram.org/bot$token/sendMessage";

$data = [
    'chat_id' => $chat_id,
    'text' => $text
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);

$result = file_get_contents($url, false, $context);

echo json_encode([
    'success' => true
]);