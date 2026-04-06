<?php

function uploadfile(array $typeFileAllowed, string $link) {

    $back = [];

    if (empty($_FILES)) {
        return [];
    }

    // 🌐 URL publique
    $baseUrl = "http://localhost/gusto/api-commande/uploads/etablissements/";

    foreach ($_FILES as $value) {

        if (!is_array($value['name'])) {
            $value['name'] = [$value['name']];
            $value['tmp_name'] = [$value['tmp_name']];
        }

        foreach ($value['name'] as $key => $filename) {

            if (!$filename) continue;

            $tmpFile = $value['tmp_name'][$key];
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($extension, $typeFileAllowed)) {
                exit(json_encode([
                    "success" => false,
                    "message" => "Type de fichier non autorisé"
                ]));
            }

            // 🔐 Hash unique du fichier
            $hash = sha1_file($tmpFile);
            $newName = $hash . '.' . $extension;

            // 📁 Créer dossier si besoin
            if (!is_dir($link)) {
                mkdir($link, 0777, true);
            }

            $serverPath = $link . $newName;

            // ⚠️ Si le fichier existe déjà → on ne ré-uploade pas
            if (!file_exists($serverPath)) {
                if (!move_uploaded_file($tmpFile, $serverPath)) {
                    exit(json_encode([
                        "success" => false,
                        "message" => "Erreur upload fichier"
                    ]));
                }
            }

            // 🔥 URL publique
            $back[] = $baseUrl . $newName;
        }
    }

    return $back;
}
