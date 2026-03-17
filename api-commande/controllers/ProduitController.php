<?php
require_once __DIR__ . '/../models/Produit.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/upload.php';

class ProduitController {

    private $produit;
    private $user; // infos JWT

    public function __construct() {
        $this->user = Middleware::checkAuth(); // récupère le token décodé
        $this->produit = new Produit();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;

        $data = $this->produit->getProduitsByEtablissement($id_etablissement);
        $rows = [];

        foreach ($data as $e) {
            $images = json_decode($e['image'], true);
            $imageHTML = '';
            if ($images) {
                foreach ($images as $l) {
                    $imageHTML .= "<img src='$l' width='40'>";
                }
            }

            $rows[] = [
                $e['nom'],
                $imageHTML,
                $e['id_categorie'],
                $e['prix'],
                $e['description'],
                "<button class='btn btn-sm btn-primary edit-produit' data-id='{$e['id_produit']}'>Modifier</button>
                 <button class='btn btn-sm btn-danger drop-produit' data-id='{$e['id_produit']}'>Supprimer</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    // =========================
    // AFFICHER UN PRODUIT
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->produit->getByIdAndRestaurant($id, $id_etablissement);

        if ($e) {
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Produit introuvable']);
        }
        exit;
    }

    // =========================
    // AJOUTER UN PRODUIT
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        // Gestion image
        if (!empty($_FILES['image'])) {
            $upload = uploadfile(['png','jpg','jpeg','gif','ico'], __DIR__.'/../uploads/images/');
            $data['image'] = json_encode($upload);
        }

        $data['id_etablissement'] = $this->user->id_etablissement;

        $id = $this->produit->create($data);
        $e  = $this->produit->getById($id);

        $row = [
            $e['nom'],
            implode(' ', array_map(fn($l)=>"<img src='$l' width='40'>", json_decode($e['image'], true))),
            $e['id_categorie'],
            $e['prix'],
            $e['description'],
            "<button class='btn btn-sm btn-primary edit-produit' data-id='{$e['id_produit']}'>Modifier</button>
             <button class='btn btn-sm btn-danger drop-produit' data-id='{$e['id_produit']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // MODIFIER UN PRODUIT
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->produit->getByIdAndRestaurant($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Produit introuvable']);
            exit;
        }

        // Gestion image
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== 4) {
            $upload = uploadfile(['png','jpg','jpeg','gif','ico'], __DIR__.'/../uploads/images/');
            $data['image'] = json_encode($upload);
        } else {
            $data['image'] = $e['image']; // garder l'ancien
        }

        $data['id_etablissement'] = $id_etablissement;
        $this->produit->update($id, $data);

        $e = $this->produit->getByIdAndRestaurant($id, $id_etablissement);

        $row = [
            $e['nom'],
            implode(' ', array_map(fn($l)=>"<img src='$l' width='40'>", json_decode($e['image'], true))),
            $e['id_categorie'],
            $e['prix'],
            $e['description'],
            "<button class='btn btn-sm btn-primary edit-produit' data-id='{$e['id_produit']}'>Modifier</button>
             <button class='btn btn-sm btn-danger drop-produit' data-id='{$e['id_produit']}'>Supprimer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // SUPPRIMER UN PRODUIT
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->produit->getByIdAndRestaurant($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Produit introuvable']);
            exit;
        }

        // Supprimer les images du dossier
        $images = json_decode($e['image'], true);
        if ($images) {
            foreach ($images as $img) {
                $path = __DIR__ . '/../' . $img;
                if (file_exists($path)) unlink($path);
            }
        }

        // Supprimer dans la base
        $this->produit->delete($id, $id_etablissement);

        echo json_encode(['success'=>true,'message'=>'Produit et images supprimés']);
        exit;
    }

}
?>