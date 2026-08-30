<?php

require_once __DIR__ . '/../core/Cors.php';
require_once __DIR__ . '/../controllers/EtablissementController.php';
require_once __DIR__ . '/../core/Middleware.php';

header('Content-Type: application/json; charset=utf-8');

$user = Middleware::checkAuth();

$controller = new EtablissementController();

$method = $_SERVER['REQUEST_METHOD'];


// =====================================================
// GET : liste ou détail
// =====================================================

if ($method === 'GET') {

    $id = isset($_GET['id'])
        ? (int) $_GET['id']
        : null;

    if ($id) {

        $controller->show($id);

    } else {

        $controller->index();
    }

    exit;
}


// =====================================================
// POST : AJOUT OU MODIFICATION
// =====================================================

if ($method === 'POST') {

    /*
     * Si le formulaire contient un fichier,
     * PHP place automatiquement les champs texte
     * dans $_POST et les fichiers dans $_FILES.
     *
     * On utilise donc directement $_POST.
     */

    $inputData = $_POST;


    // =================================================
    // ID
    // =================================================

    $id = !empty($inputData['id'])
        ? (int) $inputData['id']
        : null;


    // =================================================
    // MODIFICATION
    // =================================================

    if ($id) {

        $controller->update(
            $id,
            $inputData
        );

    }

    // =================================================
    // AJOUT
    // =================================================

    else {

        $controller->store(
            $inputData
        );
    }

    exit;
}


// =====================================================
// MÉTHODE NON AUTORISÉE
// =====================================================

http_response_code(405);

echo json_encode([
    'success' => false,
    'message' => 'Unauthorised method'
]);

exit;