<?php

require_once __DIR__ . '/../models/QrCodeModel.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

class QrCodeController {

    private $model;
    private $user;

    public function __construct() {
        $this->user = Middleware::checkAuth();
        $this->model = new QrCodeModel();
    }

    public function generate($id) {

        if (!$this->user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Unauthorized"]);
            exit;
        }

        $id_etablissement = $this->user->id_etablissement;

        if (!ctype_digit((string)$id)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid table ID"]);
            exit;
        }

        $tableData = $this->model->getByIdAndEtablissement($id, $id_etablissement);

        if (!$tableData) {
            http_response_code(404);
            echo json_encode(["error" => "Table not found"]);
            exit;
        }

        $url = $this->model->generateQrUrl(
            $id_etablissement,
            $tableData['id_table']
        );

        // 🧹 Nettoyage buffer
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // 📦 Génération QR
        $qrCode = new QrCode($url);
        $writer = new PngWriter();

        $result = $writer->write($qrCode);

        header('Content-Type: ' . $result->getMimeType());
        header('Content-Disposition: attachment; filename="qrcode.png"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        echo $result->getString();
        exit;
    }
}