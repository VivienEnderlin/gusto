<?php
require_once __DIR__ . '/../models/QrCode.php';
require_once __DIR__ . '/../core/Middleware.php';

if (!class_exists('QRcode')) {
    require_once __DIR__ . '/../utils/phpqrcode/qrlib.php';
}

class QrCodeController {

    private $user;
    private $model;

    public function __construct() {
        // 🔐 Auth obligatoire (employé)
        $this->user = Middleware::checkAuth();
        $this->model = new QrCode();

        // Nettoyage warnings
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    public function generate() {

        header('Content-Type: application/json; charset=utf-8');

        // =========================
        // Récupération des paramètres
        // =========================
        $id_etablissement = $_GET['id_etablissement'] ?? null;
        $table = $_GET['table'] ?? null;

        // =========================
        // Validation des données
        // =========================
        if (!$id_etablissement || !$table) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Paramètres manquants"
            ]);
            return;
        }

        // ID établissement doit être numérique
        if (!ctype_digit($id_etablissement)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID établissement invalide"
            ]);
            return;
        }

        // Nom table sécurisé (lettres, chiffres, espace, - _)
        if (!preg_match('/^[\w\s-]+$/u', $table)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Nom de table invalide"
            ]);
            return;
        }

        // =========================
        // Sécurité : vérifier appartenance
        // =========================
        if (!isset($this->user->id_etablissement) || $this->user->id_etablissement != $id_etablissement) {
            http_response_code(403);
            echo json_encode([
                "success" => false,
                "message" => "Accès interdit"
            ]);
            return;
        }

        // =========================
        // Génération URL sécurisée
        // =========================
        $url = $this->model->generateQrUrl($id_etablissement, $table);

        // =========================
        // Génération du QR code
        // =========================
        $filename = "qrcode_table_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', $table) . ".png";

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        // Génération image QR
        QRcode::png($url, null, QR_ECLEVEL_H, 8);

        exit;
    }
}
