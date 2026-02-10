<?php
require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Middleware.php';

class CommandeController {

    private $commande;

    public function __construct() {
        $this->commande = new Commande();
        Middleware::checkAuth(); // toutes les routes sont sécurisées JWT
    }

    // Lister toutes les commandes
    public function index() {
        Response::success($this->commande->getAllCommandes());
    }

    // Créer une nouvelle commande
    public function store($data) {
        $this->commande->create($data);
        Response::success(["message" => "Commande créée"]);
    }

    // Mettre à jour le statut / situation
    public function updateStatus($id, $data) {
        $statu = $data['statu'] ?? 'En cours';
        $situation = $data['situation'] ?? '';
        $this->commande->updateStatus($id, $statu, $situation);
        Response::success(["message" => "Statut de la commande mis à jour"]);
    }

    // Supprimer une commande
    public function delete($id) {
        $this->commande->deleteCommande($id);
        Response::success(["message" => "Commande supprimée"]);
    }

    // Récupérer une commande par ID
    public function show($id) {
        $commande = $this->commande->getById($id);
        if ($commande) {
            Response::success($commande);
        } else {
            Response::error("Commande non trouvée", 404);
        }
    }
}
