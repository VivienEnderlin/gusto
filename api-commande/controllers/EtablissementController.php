<?php
require_once __DIR__ . '/../models/Etablissement.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/upload.php';

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

            if ($e['statu'] === 'Activer') {
                $statutHTML = "<span class='statu-actif'>Activer</span>";
                $btnClass = 'danger';
                $btnText  = 'Bloquer';
            } else {
                $statutHTML = "<span class='statu-bloque'>Bloquer</span>";
                $btnClass = 'success';
                $btnText  = 'Activer';
            }

            $rows[] = [
                $logoHTML,
                $e['nom'],
                $e['type'],
                $e['adresse'],
                $e['date_enreg'],
                $statutHTML,
                "<button class='btn btn-sm btn-primary edit-ets' data-id='{$e['id_etablissement']}'>Modifier</button>
                 <button class='btn btn-sm btn-$btnClass change-ets' data-id='{$e['id_etablissement']}'>$btnText</button>"
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
            echo json_encode(['success'=>false, 'message'=>'Etablissement introuvable']);
        }
        exit;
    }


    // =========================
    // AJOUT
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_FILES['logo'])) {
            $upload = uploadfile(['png','jpg','jpeg','gif','ico'], __DIR__.'/../uploads/etablissements/');
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
            "<span class='statu-actif'>Activer</span>",
            "<button class='btn btn-sm btn-primary edit-ets' data-id='$id'>Modifier</button>
             <button class='btn btn-sm btn-danger change-ets' data-id='$id'>Bloquer</button>"
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
        $e = $this->etablissement->getById($id);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Etablissement introuvable']);
            exit;
        }

        // Gestion du logo
        if (!empty($_FILES['logo']) && $_FILES['logo']['error'] !== 4) {
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico'],
                __DIR__.'/../uploads/etablissements/'
            );
            $data['logo'] = json_encode($upload);
        } else {
            $data['logo'] = $e['logo']; // garder l'ancien
        }

        // Mise à jour
        $this->etablissement->update($id, $data);

        // Relecture
        $e = $this->etablissement->getById($id);

        // Statut + boutons
        if ($e['statu'] === 'Activer') {
            $statutHTML = "<span class='statu-actif'>Activer</span>";
            $btnClass = 'danger';
            $btnText  = 'Bloquer';
        } else {
            $statutHTML = "<span class='statu-bloque'>Bloquer</span>";
            $btnClass = 'success';
            $btnText  = 'Activer';
        }

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
            $statutHTML,
            "<button class='btn btn-sm btn-primary edit-ets' data-id='{$e['id_etablissement']}'>Modifier</button>
             <button class='btn btn-sm btn-$btnClass change-ets' data-id='{$e['id_etablissement']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }


    // =========================
    // CHANGER STATUT
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $this->etablissement->toggleStatut($id);
        $e = $this->etablissement->getById($id);

        if ($e['statu'] === 'Activer') {
            $statutHTML = "<span class='statu-actif'>Activer</span>";
            $btnClass = 'danger';
            $btnText  = 'Bloquer';
        } else {
            $statutHTML = "<span class='statu-bloque'>Bloquer</span>";
            $btnClass = 'success';
            $btnText  = 'Activer';
        }

        $row = [
            implode(' ', array_map(fn($l)=>"<img src='$l' width='40'>", json_decode($e['logo'], true))),
            $e['nom'],
            $e['type'],
            $e['adresse'],
            $e['date_enreg'],
            $statutHTML,
            "<button class='btn btn-sm btn-primary edit-ets' data-id='{$e['id_etablissement']}'>Modifier</button>
             <button class='btn btn-sm btn-$btnClass change-ets' data-id='{$e['id_etablissement']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }
}
