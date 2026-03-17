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
        $this->user = Middleware::checkAuth();
        $this->model = new QrCode();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    public function generate() {

        $etablissement = $_GET['etablissement'] ?? null;
        $table = $_GET['table'] ?? null;

        if (!$etablissement || !$table) {
            http_response_code(400);
            echo json_encode([
                "success"=>false,
                "message"=>"Paramètres invalides"
            ]);
            return;
        }

        $url = $this->model->generateQrUrl($etablissement, $table);

        $filename = "qrcode_table_{$table}.png";

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        QRcode::png($url, null, QR_ECLEVEL_H, 8);
    }
}