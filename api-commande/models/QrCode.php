<?php

class QrCode extends BaseModel {

    private $secret;

    public function __construct()
    {
        parent::__construct();
        $this->secret = getenv('secret_key');
    }

    public function getByIdAndEtablissement($id, $id_etablissement)
    {
        $stmt = $this->personnalSelect(
            "tables_restaurant",
            "*",
            "WHERE id_table = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateQrUrl($id_etablissement, $id_table)
    {
        $signature = hash_hmac(
            'sha256',
            $id_etablissement . ":" . $id_table,
            $this->secret
        );

        $data = $id_etablissement . ":" . $id_table . ":" . $signature;

        $code = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

        return "https://gusto-api-48f214a89058.herokuapp.com/web/check.php?code=" . $code;
    }
}