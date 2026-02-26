<?php
require_once __DIR__ . '/../models/Appareil.php';
require_once __DIR__ . '/../core/Middleware.php';

class AppareilController {

    private $appareil;

    public function __construct() {
        Middleware::checkAuth();
        $this->appareil = new Appareil();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $data = $this->appareil->getAllAppareils();
        $rows = [];

        foreach ($data as $e) {
            $rows[] = [
                $e['marque'],
                $e['model'],
                $e['numero_serie'],
                $e['systeme_exploitation'],
                "<button class='btn btn-sm btn-primary edit-app' data-id='{$e['id_appareil']}'>Modifier</button>
                <button class='btn btn-sm btn-danger drop-app' data-id='{$e['id_appareil']}
                    '>Supprimer</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $e = $this->appareil->getById($id);
        if ($e) {
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Etablissement introuvable']);
        }
        exit;
    }


    // =========================
    // AJOUT
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id = $this->appareil->create($data);
        $e  = $this->appareil->getById($id);

        $row = [
            $e['marque'],
            $e['model'],
            $e['numero_serie'],
            $e['systeme_exploitation'],
            "<button class='btn btn-sm btn-primary edit-app' data-id='{$e['id_appareil']}'>Modifier</button>
            <button class='btn btn-sm btn-danger drop-app' data-id='{$e['id_appareil']}
            '>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
// MODIFIER
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        // Récupération de l'existant
        $e = $this->appareil->getById($id);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Appareil introuvable']);
            exit;
        }

        // Mise à jour
        $this->appareil->update($id, $data);

        // Relecture
        $e = $this->appareil->getById($id);

        // Ligne tableau
        $row = [
            $e['marque'],
            $e['model'],
            $e['numero_serie'],
            $e['systeme_exploitation'],
            "<button class='btn btn-sm btn-primary edit-app' data-id='{$e['id_appareil']}'>Modifier</button>
            <button class='btn btn-sm btn-danger drop-app' data-id='{$e['id_appareil']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
// SUPPRIMER
// =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        // Vérifier existence
        $e = $this->appareil->getById($id);
        if (!$e) {
            echo json_encode([
                'success' => false,
                'message' => 'Appareil introuvable'
            ]);
            exit;
        }

        // Suppression
        $this->appareil->delete($id);

        echo json_encode([
            'success' => true,
            'message' => 'Appareil supprimé'
        ]);
        exit;
    }

}
