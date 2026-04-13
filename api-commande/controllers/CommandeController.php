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
    // Déterminer l'id_etablissement
    // =========================
    private function getEtablissementId() {

        // 🔐 Si token → utiliser JWT
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $user = Middleware::checkAuth();
            return $user->id_etablissement; // ✅ CORRECTION
        }

        // 🌍 Sinon → client
        if (isset($_GET['id_etablissement'])) {
            return $_GET['id_etablissement'];
        }

        echo json_encode([
            'success' => false,
            'message' => 'Establishment ID required'
        ]);
        exit;
    }

    // =========================
    // LISTE DES Commandes
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $data = $this->commande->getCommandesByEtablissement($id_etablissement);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
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
            echo json_encode(['success' => false, 'message' => 'Command not found']);
        }
        exit;
    }

    // =========================
    // AJOUTER UNE COMMANDE
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();

        $service = $this->commande->isTableActive($data['id_table'], $id_etablissement);

        if (!$service) {
            echo json_encode([
                'success' => false,
                'message' => 'Table service is unavailable'
            ]);
            exit;
        }

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
    // MODIFIER UNE COMMANDE
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');

        // 🔐 Auth obligatoire
        $user = Middleware::checkAuth();
        $id_etablissement = $user->id_etablissement; // ✅ CORRECTION

        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode([
                'success' => false,
                'message' => 'Command not found'
            ]);
            exit;
        }

        $this->commande->update($id, $id_etablissement, $data);

        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode([
            'success' => true,
            'data' => $e
        ]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE COMMANDE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        // 🔐 Auth obligatoire
        $user = Middleware::checkAuth();
        $id_etablissement = $user->id_etablissement;

        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode([
                'success' => false,
                'message' => 'Command not found'
            ]);
            exit;
        }

        $this->commande->delete($id, $id_etablissement);

        echo json_encode([
            'success' => true,
            'message' => 'Order cancelled'
        ]);
        exit;
    }


    public function getByServiceRange($debut, $fin) {

        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();

        $data = $this->commande->getByServiceRange($id_etablissement, $debut, $fin);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }

    // =========================
    // CHANGER STATUT
    // =========================
    public function changeStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        // 🔐 Auth obligatoire
        $user = Middleware::checkAuth();
        $id_etablissement = $user->id_etablissement;

        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode([
                'success' => false,
                'message' => 'Command not found'
            ]);
            exit;
        }

        $this->commande->toggleStatut($id, $id_etablissement);

        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        echo json_encode([
            'success' => true,
            'data' => $e
        ]);
        exit;
    }
}
?>