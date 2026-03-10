<?php
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../core/Middleware.php';

class CategorieController {

    private $categorie;

    public function __construct() {
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        Middleware::checkAuth();
        $this->categorie = new Categorie();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $data = $this->categorie->getCategoriesByEtablissement($id_etablissement);
        $rows = [];

        foreach ($data as $e) {
            $rows[] = [
                $e['libelle'],
                "<button class='btn btn-sm btn-primary edit-categorie' data-id='{$e['id_categorie']}'>Modifier</button>
                <button class='btn btn-sm btn-danger drop-categorie' data-id='{$e['id_categorie']}'>Supprimer</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);
        if ($e) {
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Categorie introuvable']);
        }
        exit;
    }

    // =========================
    // AJOUT
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $data['id_etablissement'] = $_SESSION['id_etablissement'];

        $id = $this->categorie->create($data);
        $e  = $this->categorie->getByIdAndEtablissement($id, $data['id_etablissement']);

        $row = [
            $e['libelle'],
            "<button class='btn btn-sm btn-primary edit-categorie' data-id='{$e['id_categorie']}'>Modifier</button>
            <button class='btn btn-sm btn-danger drop-categorie' data-id='{$e['id_categorie']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // MODIFIER
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Categorie introuvable']);
            exit;
        }

        $data['id_etablissement'] = $id_etablissement;
        $this->categorie->update($id, $data);

        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);
        $row = [
            $e['libelle'],
            "<button class='btn btn-sm btn-primary edit-categorie' data-id='{$e['id_categorie']}'>Modifier</button>
            <button class='btn btn-sm btn-danger drop-categorie' data-id='{$e['id_categorie']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // SUPPRIMER
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode([
                'success' => false,
                'message' => 'Categorie introuvable'
            ]);
            exit;
        }

        $this->categorie->delete($id, $id_etablissement);

        echo json_encode([
            'success' => true,
            'message' => 'Categorie supprimée'
        ]);
        exit;
    }

}
?>