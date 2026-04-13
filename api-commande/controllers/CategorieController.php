<?php
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../core/Middleware.php';

class CategorieController {

    private $categorie;
    private $user; // infos JWT

    public function __construct() {
        $this->user = Middleware::checkAuth(); // récupère le token décodé
        $this->categorie = new Categorie();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;

        $data = $this->categorie->getCategoriesByEtablissement($id_etablissement);
        echo json_encode(['success'=>true,'data'=>$data]);
        exit;
    }

    // =========================
    // AFFICHER UNE CATEGORIE
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);

        if ($e) {
            echo json_encode(['success'=>true,'data'=>$e]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Category not found']);
        }
        exit;
    }

    // =========================
    // AJOUTER UNE CATEGORIE
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $id = $this->categorie->create($data, $id_etablissement);
        $e  = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // MODIFIER UNE CATEGORIE
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Category not found']);
            exit;
        }

        $this->categorie->update($id, $id_etablissement, $data);

        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE CATEGORIE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->categorie->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Category not found']);
            exit;
        }

        $this->categorie->delete($id, $id_etablissement);

        echo json_encode(['success'=>true,'message'=>'Category deleted']);
        exit;
    }
}
?>