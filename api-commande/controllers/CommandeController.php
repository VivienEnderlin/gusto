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

        if (isset($headers['authorization'])) {
            $user = Middleware::checkAuth();
            return $user->id_etablissement; // ✅ CORRECTION
        }

        // 🌍 Sinon → client
        if (isset($_GET['id_etablissement'])) {
            return $_GET['id_etablissement'];
        }

        echo json_encode([
            'success' => false,
            'message' => 'Etablishment ID required'
        ]);
        exit;
    }

    public function code_verfiy() {

        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
         $id_table = $_GET['id_table'] ?? null;

          if (!$id_table) {
            echo json_encode([
                'success' => false,
                'message' => 'Table ID required'
            ]);
            exit;
        }

        $data = $this->commande->isTableActive($id_table, $id_etablissement);

        if ($data) {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'no avalaible service'
            ]);
        }

        exit;
    }

    // =========================
    // LISTE DES Commandes
    // =========================
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $data = $this->commande->getByEtablissementGroupedByTicket($id_etablissement);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }

    // =========================
    // STAT DES Commandes
    // =========================
    public function stat() {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();
        $data = $this->commande->getstatByEtablissement($id_etablissement);

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

        $id_etablissement = $this->getEtablissementId(); 

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

    public function deleteTicket($id_ticket) {

        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();

        $deleted = $this->commande->deleteByTicket($id_ticket, $id_etablissement);

        echo json_encode([
            'success' => $deleted > 0,
            'message' => $deleted > 0 ? 'Ticket cancelled' : 'Nothing deleted'
        ]);
        exit;
    }
    
    
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $id_etablissement = $this->getEtablissementId();

        $e = $this->commande->getByIdAndEtablissement($id, $id_etablissement);

        if (!$e) {
            echo json_encode([
                'success' => false,
                'message' => 'Command not found'
            ]);
            exit;
        }

        $deleted = $this->commande->delete($id, $id_etablissement);

        if ($deleted) {
            echo json_encode([
                'success' => true,
                'message' => 'Order cancelled'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Delete failed'
            ]);
        }
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
   public function changeStatus($id_ticket) {
        header('Content-Type: application/json; charset=utf-8');
        // récupérer établissement depuis token/session
        $id_etablissement = $this->getEtablissementId();
        // récupérer commandes du ticket
        $rows = $this->commande->getByTicketAndEtablissement(
            $id_ticket,
            $id_etablissement
        );
        if (!$rows || count($rows) === 0) {

            echo json_encode([
                'success' => false,
                'message' => 'Command not found'
            ]);
            exit;
        }
        // Vérifie s'il existe au moins un "En attente"
        $hasEnAttente = false;
        foreach ($rows as $row) {

            if ($row['etat'] === 'En attente') {
                $hasEnAttente = true;
                break;
            }
        }
        $updated = false;

        // PRIORITÉ AUX "En attente"
        if ($hasEnAttente) {
            foreach ($rows as $row) {

                if ($row['etat'] === 'En attente') {

                    $this->commande->updateEtatById(
                        $row['id_commande'],
                        $id_etablissement,
                        'Servi'
                    );

                    $updated = true;
                }
            }

        } else {
            // Seulement s'il n'y a plus de "En attente"
            foreach ($rows as $row) {

                if ($row['etat'] === 'Servi') {

                    $this->commande->updateEtatById(
                        $row['id_commande'],
                        $id_etablissement,
                        'Payé'
                    );

                    $updated = true;
                }
            }
        }
        if (!$updated) {

            echo json_encode([
                'success' => false,
                'message' => 'Nothing to update'
            ]);
            exit;
        }
        // récupérer nouvelles données
        $data = $this->commande->getByTicketAndEtablissement(
            $id_ticket,
            $id_etablissement
        );
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }
}
?>