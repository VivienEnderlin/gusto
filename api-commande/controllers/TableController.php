<?php
require_once __DIR__ . '/../models/Table.php';
require_once __DIR__ . '/../core/Middleware.php';

class TableController {

    private $table;
    private $user; // infos du JWT

    public function __construct() {
        $this->user = Middleware::checkAuth(); // récupère le token décodé
        $this->table = new Table();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE DES TABLES
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;

        $data = $this->table->getTablesByEtablissement($id_etablissement);
        $rows = [];

        foreach ($data as $e) {
            $rows[] = [
                $e['nom'],
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

        $id_etablissement = $this->user->id_etablissement;

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

        $data['id_etablissement'] = $this->user->id_etablissement;

        $id = $this->table->create($data);
        $e  = $this->table->getById($id);

        $row = [
            $e['nom'],
            "<a href='http://gusto/api-commande/routes/qrcode.php?etablissement=".$e['id_etablissement']."&table=".$e['nom']."' class='btn btn-sm btn-success'>Générer le QR Code</a>
            <button class='btn btn-sm btn-primary edit-table' data-id='{$e['id_table']}'>Modifier</button>
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

        $id_etablissement = $this->user->id_etablissement;

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table introuvable']);
            exit;
        }

        $this->table->update($id, $data);

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);

        $row = [
            $e['nom'],
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

        $id_etablissement = $this->user->id_etablissement;

        $e = $this->table->getByIdAndRestaurant($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table introuvable']);
            exit;
        }

        $this->table->delete($id, $id_etablissement);

        echo json_encode(['success'=>true,'message'=>'Table supprimée']);
        exit;
    }

}