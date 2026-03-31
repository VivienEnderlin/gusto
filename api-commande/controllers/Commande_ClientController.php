<?php
require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../core/Middleware.php';

class Commande_ClientController {

    private $commande;

    public function __construct() {
        $this->commande = new Commande();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // Déterminer l'id_etablissement
    // =========================
    private function getEtablissementId() {
        // 2️⃣ Client non connecté → récupérer depuis URL
        if (isset($_GET['id_etablissement'])) {
            return $_GET['id_etablissement'];
        }
         echo json_encode(['success'=>false,'message'=>'ID établissement requis']);
        exit;
    }

    // =========================
    // LISTE DES Commandes
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $data = $this->commande->getCommandesByEtablissement($id_etablissement);

        echo json_encode(['success'=>true, 'data'=>$data]);
        exit;
    }

    // =========================
    // AFFICHER UNE COMMANDE
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if ($e) {
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Commande introuvable']);
        }
        exit;
    }

    // =========================
    // AJOUTER UNE COMMANDE (Client ou employé)
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');
        $id_etablissement = $this->getEtablissementId();
        // 🔥 Vérifier si la table a un service actif
        $service = $this->commande->isTableActive(
            $data['id_table'],
            $id_etablissement
        );
        if (!$service) {
            echo json_encode([
                'success' => false,
                'message' => 'Cette table est fermée ou aucun service actif'
            ]);
            exit;
        }
        // ✅ Sinon on crée la commande
        $id = $this->commande->create($data, $id_etablissement);
        $e  = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode([
            'success' => true,
            'id_commande' => $id,
            'data' => $e
        ]);
        exit;
    }

    // =========================
    // MODIFIER UNE COMMANDE (Seulement employé connecté)
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');
        $id_etablissement = $this->getEtablissementId(); // token requis
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Commande introuvable']);
            exit;
        }
        $this->commande->update($id, $id_etablissement, $data);
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);
        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE COMMANDE (Seulement employé connecté)
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Commande introuvable']);
            exit;
        }

        $this->commande->delete($id, $id_etablissement);
        echo json_encode(['success'=>true,'message'=>'Commande supprimée']);
        exit;
    }

    // =========================
    // CHANGER STATUT (Seulement employé connecté)
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId(); // token requis
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Commande introuvable']);
            exit;
        }

        $this->commande->toggleStatut($id, $id_etablissement);
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode(['success'=>true, 'data'=>$e]);
        exit;
    }
}
?>