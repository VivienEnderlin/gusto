<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../core/Middleware.php';

class UtilisateurController {

    private $model;
    private $user; // utilisateur connecté

    private $roles = [
        0 => 'Admin',
        1 => 'Gérant',
        2 => 'Serveur'
    ];

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

            $rows[] = [
                $e['nom'],
                $e['adresse'],
                $e['telephone'],
                $this->roles[$e['role']],
                $e['date_enreg'],
                "<button class='btn btn-sm btn-primary edit-user' data-id='{$e['id_utilisateur']}'>Modifier</button>"
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
            echo json_encode(['success'=>false, 'message'=>'User not found']);
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
             $this->roles[$e['role']],
             $e['date_enreg'],
            "<button class='btn btn-sm btn-primary edit-user' data-id={$e['id_utilisateur']}>Modifier</button>"
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
            echo json_encode(['success'=>false,'message'=>'User not found']);
            exit;
        }
        // Mise à jour
        $this->model->update($id, $data);

        // Relecture
        $e = $this->model->getById($id);

        // Ligne tableau
        $row = [
            $e['nom'],
            $e['adresse'],
            $e['telephone'],
            $this->roles[$e['role']],
            $e['date_enreg'],
            "<button class='btn btn-sm btn-primary edit-user' data-id='{$e['id_utilisateur']}'>Modifier</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

}
