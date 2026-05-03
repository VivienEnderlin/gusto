<?php

function uploadfile(array $typeFileAllowed, string $link) {

    $back = [];

    if (empty($_FILES)) {
        return [];
    }

    // 🌐 URL publique
    // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
    // $host = $_SERVER['HTTP_HOST'];

    $baseUrl = "/api-commande/uploads/images/";

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
                    "message" => "Unsupported file type"
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
                        "message" => "File upload error"
                    ]));
                }
            }

            // 🔥 URL publique
            $back[] = $baseUrl . $newName;
        }
    }

    return $back;
}
