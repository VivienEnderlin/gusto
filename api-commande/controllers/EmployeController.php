<?php
require_once __DIR__ . '/../models/Employe.php';
require_once __DIR__ . '/../core/Middleware.php';

class EmployeController {

    private $model;
    private $user; // utilisateur connecté

    public function __construct() {
        // 🔐 Vérifie le token
        $this->user = Middleware::checkAuth();

        $this->model = new Utilisateur();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================

    public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $id_etablissement = $this->user->id_etablissement;

        $data = $this->model->getEmployeByEtablissement($id_etablissement);

        echo json_encode(['success'=>true,'data'=>$data]);
        exit;
    }

    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $user = $this->model->getByIdAndEtablissement($id, $id_etablissement);
        if ($user) {
            echo json_encode(['success'=>true, 'data'=>$user]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Employee not found']);
        }
        exit;
    }

    // =========================
    // CREER
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;

        $id = $this->model->create($data, $id_etablissement);
        if ($id === false) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'This item already existing.'
            ]);
            exit;
        }
        $e  = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // METTRE À JOUR
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->model->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Employee not found']);
            exit;
        }
        $this->model->update($id, $id_etablissement, $data);
        $e = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE CATEGORIE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Employee not found']);
            exit;
        }

        $this->model->delete($id, $id_etablissement);

        echo json_encode(['success'=>true,'message'=>'Employee deleted']);
        exit;
    }

    // =========================
    // RÉINITIALISER LE MOT DE PASSE
    // =========================
    public function reset($id, $data = []) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;

        // Vérifier que l'utilisateur appartient bien à l'établissement
        $e = $this->model->getByIdAndEtablissement(
            $id,
            $id_etablissement
        );

        if (!$e) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Employee not found'
            ]);

            exit;
        }

        // Réinitialiser le mot de passe
        $result = $this->model->resetPassword(
            $id,
            $id_etablissement
        );

        // Retourner le résultat du model
        if (!$result['success']) {
            http_response_code(500);

            echo json_encode($result);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Mot de passe réinitialisé et envoyé par email.'
        ]);

        exit;
    }

    

}
