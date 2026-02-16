<?php
$route = $_GET['route'] ?? '';

switch ($route) {
    case 'login':
        require '../routes/auth.php';
        break;

    case 'etablissement':
        require '../routes/etablissement.php';
        break;

    case 'commandes':
        require '../routes/commandes.php';
        break;

    default:
        echo json_encode(["error" => "Route inconnue"]);
}
