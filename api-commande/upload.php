<?php

function uploadfile($typeFileAllowed, $link) {

    $back = [];
    foreach ($_FILES as $key => $value) {

        if (empty($_FILES[$key])) {
            die(json_encode(array(0 => false, 'resp' => 'Aucun fichier disponible ...')));
        }

        if (!is_array($value["name"])) {
            $value["name"][] = $value["name"];
            $value["tmp_name"][] = $value["tmp_name"];
        }

        foreach ($value["name"] as $key1 => $value1) {

            $uploadfile = $value["tmp_name"][$key1];

            $structure = $link;
            $extension = strtolower(pathinfo($value["name"][$key1], PATHINFO_EXTENSION));

            if (!in_array($extension, $typeFileAllowed)) {
                die(json_encode(array(0 => false, 'resp' => 'Seuls les fichiers de type .png, .jpg, .jpeg, .gif, .ico sont autorisés')));
            }

            // 🆕 Nettoyer le nom de fichier (remplacer espaces par underscores)
            $originalName = pathinfo($value["name"][$key1], PATHINFO_FILENAME);
            $cleanName = preg_replace('/\s+/', '_', $originalName); // remplace les espaces par des _
            $cleanName = preg_replace('/[^a-zA-Z0-9_\-]/', '', $cleanName); // optionnel : enlever caractères spéciaux
            $fileName = $cleanName . '.' . $extension;

            if (!is_dir($structure)) {
                if (!mkdir($structure, 0777, true)) {
                    die(json_encode(array(0 => false, 'resp' => 'Erreur lors de la creation du dossier')));
                }
            }

            $back[$key1] = $structure . $fileName;

            if (!move_uploaded_file($uploadfile, $back[$key1])) {
                die(json_encode(array(0 => false, 'resp' => 'Une erreur est servenue lors de l`upload du ficher')));
            }
        }
    }

    return $back;
}
?>