<?php

require_once __DIR__ . '/../models/QrCode.php';

if (!class_exists('QRcode')) {
    require_once __DIR__ . '/../utils/phpqrcode/qrlib.php';
}

class QrCodeController {

    private $model;

    public function __construct() {
        $this->model = new QrCode();
    }

    public function generate($id) {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // ========================
        // AUTH JWT FIX
        // ========================
        $user = Middleware::checkAuth();

        if (!$user) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized"]);
            return;
        }

        $id_etablissement = $user->id_etablissement ?? null;

        if (!$id_etablissement) {
            http_response_code(400);
            echo json_encode(["error" => "Missing establishment"]);
            return;
        }

        // ========================
        // VALIDATION ID
        // ========================
        if (!ctype_digit((string)$id)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Invalid table ID"
            ]);
            return;
        }

        // ========================
        // GET TABLE
        // ========================
        $tableData = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        if (!$tableData) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Table not found"
            ]);
            return;
        }

        $id_table = $tableData['id_table'];
        $nom_table = $tableData['nom'];

        // ========================
        // GENERATE URL
        // ========================
        $url = $this->model->generateQrUrl($id_etablissement, $id_table);

        if (!$url) {
            http_response_code(500);
            echo json_encode(["error" => "QR URL generation failed"]);
            return;
        }

        // ========================
        // QR OUTPUT
        // ========================
        $filename = "qrcode_table_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', $nom_table) . ".png";

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        QRcode::png($url, null, QR_ECLEVEL_H, 8);
        exit;
    }
}