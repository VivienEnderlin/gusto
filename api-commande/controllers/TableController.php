<?php
require_once __DIR__ . '/../models/Table.php';
require_once __DIR__ . '/../core/Middleware.php';

class TableController {

    private $table;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        Middleware::checkAuth();
        $this->table = new Table();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE DES TABLES
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $data = $this->table->getTablesByEtablissement($id_etablissement);
        $rows = [];

        foreach ($data as $e) {
            $rows[] = [
                $e['Nom'],
                "<button class='btn btn-sm btn-primary edit-table' data-id='{$e['id_table']}'>Modifier</button>
                <button class='btn btn-sm btn-danger drop-table' data-id='{$e['id_table']}'>Supprimer</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    // =========================
    // AFFICHER UNE TABLE
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);
        if ($e) {
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Table introuvable']);
        }
        exit;
    }

    // =========================
    // AJOUTER UNE TABLE
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $data['id_etablissement'] = $_SESSION['id_etablissement'];

        $id = $this->table->create($data);
        $e  = $this->table->getById($id);

        $row = [
            $e['Nom'],
            "<button class='btn btn-sm btn-primary edit-table' data-id='{$e['id_table']}'>Modifier</button>
            <button class='btn btn-sm btn-danger drop-table' data-id='{$e['id_table']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // MODIFIER UNE TABLE
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table introuvable']);
            exit;
        }

        $this->table->update($id, $data);

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);

        $row = [
            $e['Nom'],
            "<button class='btn btn-sm btn-primary edit-table' data-id='{$e['id_table']}'>Modifier</button>
            <button class='btn btn-sm btn-danger drop-table' data-id='{$e['id_table']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE TABLE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table introuvable']);
            exit;
        }

        $this->table->delete($id, $id_etablissement);

        echo json_encode(['success'=>true,'message'=>'table supprimée']);
        exit;
    }

}