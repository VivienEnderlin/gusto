<?php
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../core/Middleware.php';

class ServiceController {

    private $service;
    private $user;

    public function __construct(){
        $this->user = Middleware::checkAuth(); // récupère JWT décodé
        $this->service = new Service();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================
    public function index(){
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $data = $this->service->getServicesByEtablissement($id_etablissement);

        $rows = [];
        foreach($data as $e){
            if ($e['statu'] === 'Ouvert') {
                $statutHTML = "<span class='statu-valide'>Ouvert</span>";
                $btnClass = 'danger';
                $btnText  = 'Fermer';
            } else {
                $statutHTML = "<span class='statu-expire'>Fermer</span>";
                $btnClass = 'success';
                $btnText  = 'Ouvrir';
            }

            $rows[] = [
                $e['id_table'],
                $e['id_utilisateur'],
                $e['date_heure_ouverture'],
                $e['date_heure_fermeture'],
                $statutHTML,
                "<button class='btn btn-sm btn-$btnClass edit-service' data-id='{$e['id_service']}'>$btnText</button>"
            ];
        }

        echo json_encode(['success'=>true,'data'=>$rows]);
        exit;
    }

    // =========================
    // AFFICHER
    // =========================
    public function show($id){
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->user->id_etablissement;
        $e = $this->service->getByIdAndRestaurant($id,$id_etablissement);

        if($e){
            echo json_encode(['success'=>true,'data'=>$e]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Service introuvable']);
        }
        exit;
    }

    // =========================
    // AJOUT
    // =========================
    public function store($data){
        header('Content-Type: application/json; charset=utf-8');

        $data['id_etablissement'] = $this->user->id_etablissement;
        $data['id_utilisateur']   = $this->user->id_utilisateur;
        $id = $this->service->create($data);
        $e = $this->service->getById($id);

        if ($e['statu'] === 'Ouvert') {
            $statutHTML = "<span class='statu-valide'>Ouvert</span>";
            $btnClass = 'danger';
            $btnText  = 'Fermer';
        } else {
            $statutHTML = "<span class='statu-expire'>Fermer</span>";
            $btnClass = 'success';
            $btnText  = 'Ouvrir';
        }

        $row = [
            $e['id_table'],
            $e['id_utilisateur'],
            $e['date_heure_ouverture'],
            $e['date_heure_fermeture'],
            $statutHTML,
            "<button class='btn btn-sm btn-$btnClass edit-service' data-id='{$e['id_service']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }

    // =========================
    // CHANGER STATUT
    // =========================
    public function changeStatus($id){
        header('Content-Type: application/json; charset=utf-8');

        $this->service->toggleStatut($id);
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
            $e['id_utilisateur'],
            $e['date_heure_ouverture'],
            $e['date_heure_fermeture'],
            $statutHTML,
            "<button class='btn btn-sm btn-$btnClass edit-service' data-id='{$e['id_service']}'>$btnText</button>"
        ];

        echo json_encode(['success'=>true,'data'=>$row]);
        exit;
    }
}
?>