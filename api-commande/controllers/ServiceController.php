<?php
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../core/Middleware.php';

class ServiceController {

    private $service;

    public function __construct(){
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        Middleware::checkAuth();
        $this->service = new Service();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index(){

        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $data = $this->service->getServicesByEtablissement($id_etablissement);

        $rows = [];

        foreach($data as $e){

            if ($e['statu'] === 'Ouvert') {
                $statutHTML = "<span class='statu-valide'>Ouvert</span>";
                $btnClass = 'danger';
                $btnText  = 'Fermer';
            } else {
                $statutHTML = "<span class='statu-expire'>Fermer</span>";
            }

            $rows[] = [
                $e['id_table'],
                $e['date_heure_ouverture'],
                $e['date_heure_fermeture'],
                $statutHTML,
                "<button class='btn btn-sm btn-$btnClass edit-service' data-id='{$e['id_service']}'>$btnText/button>
"
            ];
        }

        echo json_encode([
            'success'=>true,
            'data'=>$rows
        ]);

        exit;
    }

    // =========================
    // AFFICHER
    // =========================
    public function show($id){

        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $_SESSION['id_etablissement'];

        $e = $this->service->getByIdAndRestaurant($id,$id_etablissement);

        if($e){

            echo json_encode([
                'success'=>true,
                'data'=>$e
            ]);

        }else{

            echo json_encode([
                'success'=>false,
                'message'=>'Service introuvable'
            ]);
        }

        exit;
    }

    // =========================
    // AJOUT
    // =========================
    public function store($data){

        header('Content-Type: application/json; charset=utf-8');

        $data['id_etablissement'] = $_SESSION['id_etablissement'];

        $id = $this->service->create($data);

        $e = $this->service->getById($id);

        if ($e['statu'] === 'Ouvert') {
            $statutHTML = "<span class='statu-valide'>Ouvert</span>";
            $btnClass = 'danger';
            $btnText  = 'Fermer';
        } else {
            $statutHTML = "<span class='statu-expire'>Fermer</span>";
        }

        $row = [
            $e['id_table'],
            $e['date_heure_ouverture'],
            $e['date_heure_fermeture'],
            $statutHTML,
            "<button class='btn btn-sm btn-$btnClass edit-service' data-id='{$e['id_service']}'>$btnText</button>"
        ];

        echo json_encode([
            'success'=>true,
            'data'=>$row
        ]);

        exit;
    }

    // =========================
    // CHANGER STATUT
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $this->contrat->toggleStatut($id);
        $e = $this->contrat->getById($id);

        if ($e['statu'] === 'Ouvert') {
            $statutHTML = "<span class='statu-valide'>Ouvert</span>";
            $btnClass = 'danger';
            $btnText  = 'Fermer';
        } else {
            $statutHTML = "<span class='statu-expire'>Fermer</span>";
        }

        $row = [
            $e['id_table'],
            $e['date_heure_ouverture'],
            $e['date_heure_fermeture'],
            $statutHTML,
            "<button class='btn btn-sm btn-$btnClass edit-service' data-id='{$e['id_service']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }
}