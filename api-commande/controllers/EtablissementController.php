<?php
require_once __DIR__ . '/../models/Etablissement.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../config/upload.php';

class EtablissementController {

    private $etablissement;

    public function __construct() {
        Middleware::checkAuth();
        $this->etablissement = new Etablissement();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $data = $this->etablissement->getAllEtablissements();
        $rows = [];

        foreach ($data as $e) {

            $logos = json_decode($e['logo'], true);
            $logoHTML = '';
            if ($logos) {
                foreach ($logos as $l) {
                    $logoHTML .= "<img src='$l' width='40'>";
                }
            }

            $rows[] = [
                $logoHTML,
                $e['nom'],
                $e['type'],
                $e['adresse'],
                $e['date_enreg'],
                $e['id_etablissement'],
                "<button class='btn btn-sm btn-primary edit-ets' data-id='{$e['id_etablissement']}'>Modifier</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $e = $this->etablissement->getById($id);
        if ($e) {
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Establishment not found']);
        }
        exit;
    }


    // =========================
    // AJOUT
    // =========================
    public function store($data) {
        var_dump("ETAPE 3 : STORE ATTEINT");
var_dump("DATA :", $data);
var_dump("FILES :", $_FILES);

        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_FILES['logo'])) {
            var_dump("ETAPE 4 : logo détecté");
    var_dump($_FILES['logo']);
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico']
            );
             var_dump("ETAPE 5 : upload terminé");
    var_dump($upload);
            $data['logo'] = json_encode($upload);
        }

        $id = $this->etablissement->create($data);
        $e  = $this->etablissement->getById($id);

        $row = [
            implode(' ', array_map(fn($l)=>"<img src='$l' width='40'>", json_decode($e['logo'], true))),
            $e['nom'],
            $e['type'],
            $e['adresse'],
            $e['date_enreg'],
            "<button class='btn btn-sm btn-primary edit-ets' data-id={$e['id_etablissement']}>Modifier</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
// MODIFIER
    // =========================
    public function update($id, $data) {
        var_dump("ETAPE 3 : STORE ATTEINT");
var_dump("DATA :", $data);
var_dump("FILES :", $_FILES);
        header('Content-Type: application/json; charset=utf-8');

        // Récupération de l'existant
        $e = $this->etablissement->getById($id);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Establishment not found']);
            exit;
        }

        // Gestion du logo
        if (!empty($_FILES['logo']) && $_FILES['logo']['error'] !== 4) {
            var_dump("ETAPE 4 : logo détecté");
    var_dump($_FILES['logo']);
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico']
            );
            var_dump("ETAPE 5 : upload terminé");
    var_dump($upload);
            $data['logo'] = json_encode($upload);
        } else {
            $data['logo'] = $e['logo']; // garder l'ancien
        }

        // Mise à jour
        $this->etablissement->update($id, $data);

        // Relecture
        $e = $this->etablissement->getById($id);

        // Ligne tableau
        $row = [
            implode(' ', array_map(
                fn($l)=>"<img src='$l' width='40'>",
                json_decode($e['logo'], true)
            )),
            $e['nom'],
            $e['type'],
            $e['adresse'],
            $e['date_enreg'],
            "<button class='btn btn-sm btn-primary edit-ets' data-id='{$e['id_etablissement']}'>Modifier</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }
}


