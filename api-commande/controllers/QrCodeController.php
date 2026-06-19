<?php
require_once __DIR__ . '/../models/QrCode.php';
require_once __DIR__ . '/../core/Middleware.php';

if (!class_exists('QRcode')) {
    require_once __DIR__ . '/../utils/phpqrcode/qrlib.php';
}

class QrCodeController {

    private $model;
    private $user;

    public function __construct() {
        // 🔐 Auth obligatoire (employé)
        $this->user = Middleware::checkAuth();
        $this->model = new QrCode();

        // Nettoyage warnings
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    public function generate($id) {
        try {

            if (!$this->user || !isset($this->user->id_etablissement)) {
                http_response_code(401);
                echo "Unauthorized";
                exit;
            }

            $id_etablissement = $this->user->id_etablissement;
            if (!$id || !ctype_digit((string)$id)) {
                http_response_code(400);
                echo "Invalid ID";
                exit;
            }

            $tableData = $this->model->getByIdAndEtablissement($id, $id_etablissement);

            if (!$tableData) {
                http_response_code(404);
                echo "Not found";
                exit;
            }

            $url = $this->model->generateQrUrl(
                $id_etablissement,
                $tableData['id_table']
            );

            while (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: image/png');
            header('Content-Disposition: attachment; filename="qrcode.png"');

            QRcode::png($url, false, QR_ECLEVEL_H, 8);

            exit;

        } catch (Throwable $e) {
            http_response_code(500);
            echo $e->getMessage();
            exit;
        }
    }
}
