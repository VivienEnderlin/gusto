<?php
require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../core/Middleware.php';

class CommandeController {

    private $commande;
    

    public function __construct() {
        $this->commande = new Commande();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // Déterminer l'id_etablissement selon token ou URL
    // =========================
    private function getEtablissementId() {
        // 1️⃣ Vérifier si un token est présent
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $user = Middleware::verifyToken($token);
            if ($user) {
                // Token valide → récupérer l'id_etablissement depuis le token
                return $user['id_etablissement'];
            }
        }

        // 2️⃣ Sinon, client non authentifié → récupérer depuis URL
        if (isset($_GET['id_etablissement'])) {
            return $_GET['id_etablissement'];
        }

        echo json_encode(['success' => false, 'message' => 'ID établissement requis']);
        exit;
    }

    // =========================
    // LISTE DES Commandes
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $data = $this->commande->getCommandesByEtablissement($id_etablissement);

        echo json_encode(['success' => true, 'data' => $data]);
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
            echo json_encode(['success' => true, 'data' => $e]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
        }
        exit;
    }

    // =========================
    // AJOUTER UNE COMMANDE (Client ou employé)
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();

        // Vérifier si la table a un service actif
        $service = $this->commande->isTableActive($data['id_table'], $id_etablissement);
        if (!$service) {
            echo json_encode([
                'success' => false,
                'message' => 'Service de la table indisponible'
            ]);
            exit;
        }

        // Créer la commande
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

        // Obligatoire : token valide pour modifier
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            echo json_encode(['success' => false, 'message' => 'Token requis']);
            exit;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = Middleware::verifyToken($token);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Token invalide']);
            exit;
        }

        $id_etablissement = $user['id_etablissement'];
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
            exit;
        }

        $this->commande->update($id, $id_etablissement, $data);
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);
        echo json_encode(['success' => true, 'data' => $e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE COMMANDE (Seulement employé connecté)
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            echo json_encode(['success' => false, 'message' => 'Token requis']);
            exit;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = Middleware::verifyToken($token);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Token invalide']);
            exit;
        }

        $id_etablissement = $user['id_etablissement'];
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
            exit;
        }

        $this->commande->delete($id, $id_etablissement);
        echo json_encode(['success' => true, 'message' => 'Commande supprimée']);
        exit;
    }

    // =========================
    // CHANGER STATUT (Seulement employé connecté)
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            echo json_encode(['success' => false, 'message' => 'Token requis']);
            exit;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = Middleware::verifyToken($token);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Token invalide']);
            exit;
        }

        $id_etablissement = $user['id_etablissement'];
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);
        if (!$e) {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable']);
            exit;
        }

        $this->commande->toggleStatut($id, $id_etablissement);
        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);
        echo json_encode(['success' => true, 'data' => $e]);
        exit;
    }
}
?>