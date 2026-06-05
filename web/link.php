<?php
    $code = $_GET['code'] ?? null;
    $secret = "CLE_SECRETE_GUSTO"; // ✅ AJOUT OBLIGATOIRE
    if (!$code) {
        exit("Lien invalide");
    }
    // remettre base64 standard
    $code = strtr($code, '-_', '+/');
    // remettre padding '='
    $pad = strlen($code) % 4;
    if ($pad) {
        $code .= str_repeat('=', 4 - $pad);
    }
    $decoded = base64_decode($code, true);
    if (!$decoded) exit("QR code invalide");
    // Extraire id, table et signature
    $parts = explode(":", $decoded);
    if (count($parts) !== 3) exit("QR code invalide");
    list($id_etablissement, $id_table, $signature) = $parts;
    // Vérifier format id/table
    if (!ctype_digit($id_etablissement) || !ctype_digit($id_table)) {
        exit("QR code modifié ou invalide");
    }
    // Vérifier la signature
    $expected = hash_hmac('sha256', $id_etablissement . ":" . $id_table, $secret);
    // var_dump($signature); 
    // var_dump($expected);
    if (!hash_equals($expected, $signature)) {
        exit("QR code modifié ou invalide");
    }


    require_once './../api-commande/models/Etablissement.php';
    $etablissementModel = new Etablissement();
    $etablissements = $etablissementModel->getById($id_etablissement);

    require_once './../api-commande/models/Table.php';
    $tableModel = new Table();
    $tableData = $tableModel->getTable($id_etablissement, $id_table); // méthode à créer pour vérifier table précise
    if (!$tableData) {
        exit("Table invalide");
    }
    $nom_table = $tableData['nom'];
    echo "Etablissement: $id_etablissement, Table: $nom_table";
?>