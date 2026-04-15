<?php
require_once __DIR__ . '/../models/Produit.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../config/upload.php';

class ProduitController {

    private $produit;
    private $user; // infos JWT

    public function __construct() {
        $this->user = Middleware::checkAuth(); // récupère le token décodé
        $this->produit = new Produit();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE DES PRODUITS
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $data = $this->produit->getProduitsByEtablissement($id_etablissement);
        $rows = [];

        foreach ($data as $e) {
            $rows[] = [
                'id_produit' => $e['id_produit'],
                'nom' => $e['nom'],
                'image' => json_decode($e['image'], true), // renvoie un tableau d'images
                'id_categorie' => $e['id_categorie'],
                'prix' => $e['prix'],
                'description' => $e['description'],
                'id_etablissement' => $e['id_etablissement']
            ];
        }

        echo json_encode(['success'=>true, 'data'=>$rows]);
        
        exit;
    }

    // =========================
    // AFFICHER UN PRODUIT
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->produit->getByIdAndEtablissement($id, $id_etablissement);

        if ($e) {
            $e['image'] = json_decode($e['image'], true); // tableau d'images
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Product not found']);
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

        $id_etablissement = $this->user->id_etablissement;
        $id = $this->produit->create($data, $id_etablissement);
        $e  = $this->produit->getByIdAndEtablissement($id, $id_etablissement);
        $e['image'] = json_decode($e['image'], true);

        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }

    // =========================
    // MODIFIER UN PRODUIT
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->produit->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Product not found']);
            exit;
        }

        // Gestion image
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== 4) {
            $upload = uploadfile(['png','jpg','jpeg','gif','ico'], __DIR__.'/../uploads/images/');
            $data['image'] = json_encode($upload);
        } else {
            $data['image'] = $e['image']; // garder l'ancien
        }

        $this->produit->update($id, $id_etablissement, $data);
        $e = $this->produit->getByIdAndEtablissement($id, $id_etablissement);
        $e['image'] = json_decode($e['image'], true);

        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UN PRODUIT
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->produit->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Product not found']);
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

        echo json_encode(['success'=>true,'message'=>'Product removed']);
        exit;
    }

}
?>