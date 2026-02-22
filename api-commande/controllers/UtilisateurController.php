<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class UtilisateurController {

    private $model;
    private $user; // utilisateur connecté

    public function __construct() {
        // 🔐 Vérifie le token
        $this->user = Middleware::checkAuth();

        $this->model = new Utilisateur();
    }

    // =========================
    // LISTE
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->model->getAllUsers();
        $rows = [];
        foreach ($data as $e) {

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
                $e['nom'],
                $e['adresse'],
                $e['email'],
                $e['telephone'],
                $e['id_etablissement'],
                $e['date_enreg'],
                $statutHTML,
                "<button class='btn btn-sm btn-primary edit-user' data-id='{$e['id_utilisateur']}'>Modifier</button>
                 <button class='btn btn-sm btn-$btnClass change-user' data-id='{$e['id_utilisateur']}'>$btnText</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');
        $user = $this->model->getById($id);
        if ($user) {
            echo json_encode(['success'=>true, 'data'=>$user]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Utilisateur introuvable']);
        }
        exit;
    }

    // =========================
    // CREER
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');
        $id = $this->model->create($data);
        $e  = $this->model->getById($id);
        $row = [
             $e['nom'],
             $e['adresse'],
             $e['telephone'],
             $e['id_etablissement'],
             $e['date_enreg'],
             "<span class='statu-actif'>Activer</span>",
            "<button class='btn btn-sm btn-primary edit-user' data-id='$id'>Modifier</button>
             <button class='btn btn-sm btn-danger change-user' data-id='$id'>Bloquer</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // METTRE À JOUR
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');
        $e = $this->model->getById($id);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Utilisateur introuvable']);
            exit;
        }
        // Mise à jour
        $this->model->update($id, $data);

        // Relecture
        $e = $this->model->getById($id);

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
            $e['nom'],
            $e['adresse'],
            $e['telephone'],
            $e['id_etablissement'],
            $e['date_enreg'],
            $statutHTML,
            "<button class='btn btn-sm btn-primary edit-user' data-id='{$e['id_utilisateur']}'>Modifier</button>
            <button class='btn btn-sm btn-$btnClass change-user' data-id='{$e['id_utilisateur']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // CHANGER STATUT
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $this->model->toggleStatut($id);
        $e = $this->model->getById($id);

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
            $e['nom'],
            $e['adresse'],
            $e['telephone'],
            $e['id_etablissement'],
            $e['date_enreg'],
            $statutHTML,
            "<button class='btn btn-sm btn-primary edit-user' data-id='{$e['id_utilisateur']}'>Modifier</button>
            <button class='btn btn-sm btn-$btnClass change-user' data-id='{$e['id_utilisateur']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }
}
