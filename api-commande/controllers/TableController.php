<?php
require_once __DIR__ . '/../models/Table.php';
require_once __DIR__ . '/../core/Middleware.php';

class TableController {

    private $table;
    private $user; // infos JWT

    public function __construct() {
        $this->user = Middleware::checkAuth();
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
        echo json_encode(['success'=>true, 'data'=>$data]);
        exit;
    }

    // =========================
    // AFFICHER UNE TABLE
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->table->getByIdAndEtablissement($id, $id_etablissement);

        if ($e) {
            // Renvoie toutes les données brutes
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Table not found']);
        }
        exit;
    }

    // =========================
    // AJOUTER UNE TABLE
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $id = $this->table->create($data, $id_etablissement);
        $e  = $this->table->getByIdAndEtablissement($id, $id_etablissement);

        // Renvoie toutes les données brutes
        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }

    // =========================
    // MODIFIER UNE TABLE
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->table->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table not found']);
            exit;
        }

        $this->table->update($id, $id_etablissement, $data);
        $e = $this->table->getByIdAndEtablissement($id, $id_etablissement);

        // Renvoie toutes les données brutes
        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE TABLE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->table->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table not found']);
            exit;
        }

        $this->table->delete($id, $id_etablissement);

        echo json_encode(['success'=>true,'message'=>'Table deleted']);
        exit;
    }

    // =========================
    // CHANGER STATUT
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $id_utilisateur   = $this->user->id_utilisateur;

        $e = $this->table->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Table not found']);
            exit;
        }

        // Changer le statut et gérer les tables
        $this->table->toggleStatut($id, $id_utilisateur, $id_etablissement);

        // Recharger la table pour renvoyer toutes les données
        $e = $this->table->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }
}
?>