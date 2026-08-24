<?php

require_once __DIR__ . '/../models/QrCodeModel.php';
require_once __DIR__ . '/../core/Middleware.php';

class QrCodeController {

    private $model;
    private $user;

    public function __construct() {
        $this->user = Middleware::checkAuth();
        $this->model = new QrCodeModel();
    }

    public function generate($id) {

        // 🔥 Clean buffer
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // ========================
        // AUTH
        // ========================
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
        // VALIDATION ID
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

        // ========================
        // QR URL
        // ========================
        $url = $this->model->generateQrUrl(
            $id_etablissement,
            $tableData['id_table']
        );

        if (empty($url)) {
            http_response_code(500);
            echo json_encode(["error" => "QR URL generation failed"]);
            exit;
        }

        // ========================
        // EXTERNAL QR GENERATION (NO GD)
        // ========================
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);

        // ========================
        // AFFICHER LE QR CODE
        // ========================

        $image = file_get_contents($qrUrl);

        if ($image === false) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                "success" => false,
                "message" => "Impossible de générer le QR code"
            ]);
            exit;
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        echo $image;
        exit;
    }
}