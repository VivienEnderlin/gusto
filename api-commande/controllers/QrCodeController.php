<?php

require_once __DIR__ . '/../models/QrCodeModel.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../utils/phpqrcode/qrlib.php';

class QrCodeController {

    private $model;
    private $user; // utilisateur connecté

    public function __construct() {
        $this->user = Middleware::checkAuth();
        $this->model = new QrCodeModel();
    }

    public function generate($id) {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // 🔥 START CLEAN BUFFER (IMPORTANT)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if (!$this->user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized"]);
            exit;
        }

        $id_etablissement = $this->user->id_etablissement;

        if (!$id_etablissement) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Missing establishment"]);
            exit;
        }

        // ========================
        // VALIDATION
        // ========================
        if (!ctype_digit((string)$id)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Invalid table ID"]);
            exit;
        }

        // ========================
        // TABLE
        // ========================
        $tableData = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        if (!$tableData) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Table not found"]);
            exit;
        }

        $url = $this->model->generateQrUrl(
            $id_etablissement,
            $tableData['id_table']
        );

        // 🔥 CLEAN AGAIN BEFORE IMAGE
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        ini_set('zlib.output_compression', 'Off');

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="qrcode.png"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        QRcode::png($url, null, QR_ECLEVEL_H, 8);

        exit;
    }
}