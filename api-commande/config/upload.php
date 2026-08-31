<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


/**
 * Upload des fichiers vers Amazon S3
 */
function uploadfile(array $typeFileAllowed, string $link = ''): array
{
    var_dump("UPLOAD 1 : fonction uploadfile atteinte");

    $back = [];

    if (empty($_FILES)) {
        var_dump("UPLOAD 2 : \$_FILES est vide");
        return [];
    }

    var_dump("UPLOAD 3 : \$_FILES contient un fichier");


    // =====================================================
    // CONFIGURATION AWS
    // =====================================================

    $bucket = getenv('AWS_BUCKET');
    $region = getenv('AWS_REGION');
    $accessKey = getenv('AWS_ACCESS_KEY_ID');
    $secretKey = getenv('AWS_SECRET_ACCESS_KEY');

    var_dump("UPLOAD 4 : configuration récupérée");
    var_dump("bucket =", $bucket);
    var_dump("region =", $region);
    var_dump("accessKey présent =", !empty($accessKey));
    var_dump("secretKey présent =", !empty($secretKey));


    // =====================================================
    // VERIFIER CONFIGURATION AWS
    // =====================================================

    if (
        empty($bucket) ||
        empty($region) ||
        empty($accessKey) ||
        empty($secretKey)
    ) {

        var_dump("UPLOAD ERREUR : configuration AWS manquante");

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Configuration Amazon S3 manquante"
        ]);

        exit;
    }

    var_dump("UPLOAD 5 : configuration AWS OK");


    // =====================================================
    // CONNEXION A AMAZON S3
    // =====================================================

    try {

        var_dump("UPLOAD 6 : création du client S3");

        $s3 = S3Client::factory([
            'key'    => $accessKey,
            'secret' => $secretKey,
            'region' => $region
        ]);

        var_dump("UPLOAD 7 : client S3 créé");

    } catch (\Throwable $e) {

        var_dump("ERREUR CREATION S3");
        var_dump("MESSAGE :", $e->getMessage());
        var_dump("FICHIER :", $e->getFile());
        var_dump("LIGNE :", $e->getLine());

        exit;
    }


    // =====================================================
    // TRAITEMENT DES FICHIERS
    // =====================================================

    var_dump("UPLOAD 8 : début traitement fichier");

    foreach ($_FILES as $value) {

        var_dump("UPLOAD 9 : fichier trouvé");

        if (!isset($value['name'])) {
            continue;
        }


        // =================================================
        // FICHIER UNIQUE
        // =================================================

        if (!is_array($value['name'])) {

            $value['name'] = [$value['name']];
            $value['tmp_name'] = [$value['tmp_name']];
            $value['error'] = [$value['error']];
        }


        // =================================================
        // PLUSIEURS FICHIERS
        // =================================================

        foreach ($value['name'] as $key => $filename) {

            if (!$filename) {
                continue;
            }


            // =================================================
            // VERIFIER ERREUR UPLOAD
            // =================================================

            if (
                !isset($value['error'][$key]) ||
                $value['error'][$key] !== UPLOAD_ERR_OK
            ) {

                http_response_code(400);

                echo json_encode([
                    "success" => false,
                    "message" => "Erreur lors de l'upload du fichier"
                ]);

                exit;
            }


            // =================================================
            // FICHIER TEMPORAIRE
            // =================================================

            $tmpFile = $value['tmp_name'][$key];


            // =================================================
            // VERIFIER FICHIER
            // =================================================

            if (!file_exists($tmpFile)) {

                http_response_code(400);

                echo json_encode([
                    "success" => false,
                    "message" => "Fichier temporaire introuvable"
                ]);

                exit;
            }


            // =================================================
            // EXTENSION
            // =================================================

            $extension = strtolower(
                pathinfo($filename, PATHINFO_EXTENSION)
            );


            if (!in_array($extension, $typeFileAllowed, true)) {

                http_response_code(400);

                echo json_encode([
                    "success" => false,
                    "message" => "Type de fichier non supporté"
                ]);

                exit;
            }


            // =================================================
            // HASH DU FICHIER
            // =================================================

            $hash = sha1_file($tmpFile);

            if ($hash === false) {

                http_response_code(500);

                echo json_encode([
                    "success" => false,
                    "message" => "Impossible de calculer le hash du fichier"
                ]);

                exit;
            }


            // =================================================
            // NOM DU FICHIER
            // =================================================

            $newName = $hash . '.' . $extension;


            // =================================================
            // CHEMIN DANS LE BUCKET
            // =================================================

            $keyName = 'images/' . $newName;


            var_dump("UPLOAD 10 : avant envoi S3");
            var_dump("Nom :", $filename);
            var_dump("Extension :", $extension);
            var_dump("Temp :", $tmpFile);
            var_dump("Key :", $keyName);


            // =================================================
            // ENVOI VERS S3
            // =================================================

            try {

                var_dump("UPLOAD 11 : putObject va être exécuté");
                var_dump("CURL VERSION :", curl_version());
var_dump("PHP VERSION :", PHP_VERSION);

                $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $keyName,
                    'SourceFile' => $tmpFile,
                    'ContentType' => mime_content_type($tmpFile)
                ]);

                var_dump("UPLOAD 12 : putObject terminé");


                


            } catch (\Throwable $e) {

                var_dump("ERREUR PUTOBJECT");
                var_dump("MESSAGE :", $e->getMessage());
                var_dump("FICHIER :", $e->getFile());
                var_dump("LIGNE :", $e->getLine());

                exit;
            }
        }
    }


    var_dump("UPLOAD 13 : uploadfile terminé");

    return $back;
}


/**
 * Supprimer une image de S3
 */
function deleteFileFromS3(string $imageUrl): bool
{
    $bucket = getenv('AWS_BUCKET');
    $region = getenv('AWS_REGION');
    $accessKey = getenv('AWS_ACCESS_KEY_ID');
    $secretKey = getenv('AWS_SECRET_ACCESS_KEY');


    if (
        empty($bucket) ||
        empty($region) ||
        empty($accessKey) ||
        empty($secretKey)
    ) {
        return false;
    }


    // =====================================================
    // CONNEXION S3
    // =====================================================

    try {

        $s3 = S3Client::factory([
            'key'    => $accessKey,
            'secret' => $secretKey,
            'region' => $region
        ]);

    } catch (\Throwable $e) {

        return false;
    }


    // =====================================================
    // RECUPERER LE CHEMIN DU FICHIER
    // =====================================================

    $path = parse_url($imageUrl, PHP_URL_PATH);

    if (!$path) {
        return false;
    }


    $key = ltrim($path, '/');


    // =====================================================
    // SUPPRESSION
    // =====================================================

    try {

        $s3->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key
        ]);

        return true;

    } catch (\Throwable $e) {

        return false;
    }
}