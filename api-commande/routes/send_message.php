<?php
require_once __DIR__ . '/../core/Cors.php';
require __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

$nom = $_POST['nom'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$message = $_POST['message'] ?? '';

$token = getenv('TELEGRAM_TOKEN');

$chat_id = getenv('ID');

//ce qui marche en local

// $token = $_ENV['TELEGRAM_TOKEN'];

// $chat_id = $_ENV['ID'];

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