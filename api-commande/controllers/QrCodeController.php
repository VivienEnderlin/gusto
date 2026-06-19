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

        // 🔥 CLEAN OUTPUT ABSOLU
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // ========================
        // FAKE AUTH (temp test)
        // ========================
        $id_etablissement = 1;

        if (!ctype_digit((string)$id)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Invalid ID"]);
            exit;
        }

        // ========================
        // GET TABLE
        // ========================
        $table = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        if (!$table) {
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
            $table['id_table']
        );

        // 🔥 CLEAN AGAIN
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        ini_set('zlib.output_compression', 'Off');

        // ========================
        // HEADERS IMAGE
        // ========================
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="qrcode.png"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        // ========================
        // GENERATE QR
        // ========================
        QRcode::png($url, null, QR_ECLEVEL_H, 8);

        exit;
    }
}