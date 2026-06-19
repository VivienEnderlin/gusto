<?php

require_once __DIR__ . '/../models/QrCode.php';
require_once __DIR__ . '/../core/Middleware.php';

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

    // 🔥 FORCE CLEAN ENV
    while (ob_get_level()) {
        ob_end_clean();
    }

    ob_start();

    $user = Middleware::checkAuth();

    $id_etablissement = $user->id_etablissement ?? null;

    if (!$id_etablissement || !ctype_digit((string)$id)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(["error" => "Invalid request"]);
        exit;
    }

    $tableData = $this->model->getByIdAndEtablissement($id, $id_etablissement);

    if (!$tableData) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(["error" => "Not found"]);
        exit;
    }

    $url = $this->model->generateQrUrl($id_etablissement, $tableData['id_table']);

    // 🔥 CRITICAL CLEAN BEFORE IMAGE
    ob_end_clean();

    ini_set('zlib.output_compression', 'Off');

    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="qr.png"');
    header('Cache-Control: no-cache, no-store, must-revalidate');

    QRcode::png($url, null, QR_ECLEVEL_H, 8);
    exit;
}
}