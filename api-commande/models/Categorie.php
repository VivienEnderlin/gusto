<?php
require_once __DIR__ . '/../config/database.php';

class Categorie {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT idcategorie, libelle FROM categorie ORDER BY libelle ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
