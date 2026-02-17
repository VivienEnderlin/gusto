<?php
require_once __DIR__ . '/../models/Etablissement.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/upload.php';

class EtablissementController {

    private $etablissement;

    public function __construct() {
        Middleware::checkAuth(); // Vérification JWT
        $this->etablissement = new Etablissement();
        // Désactiver les notices/warnings pour éviter JSON cassé
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // Liste tous les établissements
    public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->etablissement->getAllEtablissements();

        $rows = [];
        foreach ($data as $e) {
            $logos = json_decode($e['logo'], true);
            $logoHTML = '';
            if ($logos) {
                foreach ($logos as $l) $logoHTML .= "<img src='$l' width='50'>";
            }

            $rows[] = [
                $logoHTML,
                $e['nom'],
                $e['type'],
                $e['adresse'],
                $e['dateenreg'],
                "<button class='btn btn-sm btn-primary edit-btn' data-id='{$e['id_etablissement']}'>Modifier</button>
                <button class='btn btn-sm btn-success change-btn' data-id='{$e['id_etablissement']}'>Débloquer</button>
                <button class='btn btn-sm btn-danger delete-btn' data-id='{$e['id_etablissement']}'>Bloquer</button>"
            ];
        }

        echo json_encode(['success'=>true, 'data'=>$rows]);
        exit;
    }

    // Récupérer un établissement par ID
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->etablissement->getById($id);
        if ($data) {
            echo json_encode(['success'=>true, 'data'=>$data]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Etablissement non trouvé']);
        }
        exit;
    }

    // Ajouter un établissement
    // store()
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_FILES['logo'])) {
            $upload = uploadfile(['png','jpg','jpeg','gif','ico'], __DIR__.'/../uploads/etablissements/');
            $data['logo'] = json_encode($upload);
        }

        $data['dateenreg'] = date('Y-m-d');
        $id = $this->etablissement->create($data);

        $row = [
            implode(' ', array_map(fn($l)=>"<img src='$l' width='50'>", json_decode($data['logo'] ?? '[]', true))),
            $data['nom'],
            $data['type'],
            $data['adresse'],
            $data['dateenreg'],
            "<button class='btn btn-sm btn-primary edit-btn' data-id='{$id}'>Modifier</button>
             <button class='btn btn-sm btn-success change-btn' data-id='{$id}'>Débloquer</button>
             <button class='btn btn-sm btn-danger delete-btn' data-id='{$id}'>Bloquer</button>"
        ];

        echo json_encode(['success'=>true, 'id'=>$id, 'data'=>$row]);
        exit;
    }

    // update()
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        $e = $this->etablissement->getById($id); // récupérer ancien enregistrement

        // Si on a un nouveau fichier
        if (!empty($_FILES['logo']) && $_FILES['logo']['error'] !== 4) { // 4 = pas de fichier uploadé
            $upload = uploadfile(['png','jpg','jpeg','gif','ico'], __DIR__.'/../uploads/etablissements/');
            $data['logo'] = json_encode($upload);
        } else {
            // Sinon on garde l'ancien logo
            $data['logo'] = $e['logo'];
        }

        $this->etablissement->update($id, $data);

        // Préparer la ligne pour DataTable
        $e = $this->etablissement->getById($id);
        $row = [
            implode(' ', array_map(fn($l)=>"<img src='$l' width='50'>", json_decode($e['logo'], true))),
            $e['nom'],
            $e['type'],
            $e['adresse'],
            $e['dateenreg'],
            "<button class='btn btn-sm btn-primary edit-btn' data-id='{$e['id_etablissement']}'>Modifier</button>
             <button class='btn btn-sm btn-success change-btn' data-id='{$e['id_etablissement']}'>Débloquer</button>
             <button class='btn btn-sm btn-danger delete-btn' data-id='{$e['id_etablissement']}'>Bloquer</button>"
        ];

        echo json_encode(['success'=>true, 'id'=>$id, 'data'=>$row]);
        exit;
    }


    // delete()
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');
        $this->etablissement->delete($id);
        echo json_encode(['success'=>true, 'message'=>'Etablissement supprimé']);
        exit;
    }

}
