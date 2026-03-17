<?php
require_once __DIR__ . '/BaseModel.php';

class QrCode extends BaseModel {

    public function generateQrUrl($etablissement, $table)
    {
        $data = $etablissement . ":" . $table;

        $code = base64_encode($data);

        return "http://gusto/web/menu.php?code=" . $code;
    }
}