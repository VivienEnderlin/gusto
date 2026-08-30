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
    $back = [];

    if (empty($_FILES)) {
        return [];
    }

    // =====================================================
    // CONFIGURATION AWS
    // =====================================================

    $bucket = $_ENV['AWS_BUCKET'] ?? getenv('AWS_BUCKET');
    $region = $_ENV['AWS_REGION'] ?? getenv('AWS_REGION');
    $accessKey = $_ENV['AWS_ACCESS_KEY_ID'] ?? getenv('AWS_ACCESS_KEY_ID');
    $secretKey = $_ENV['AWS_SECRET_ACCESS_KEY'] ?? getenv('AWS_SECRET_ACCESS_KEY');


    // =====================================================
    // VERIFIER LA CONFIGURATION
    // =====================================================

    if (
        empty($bucket) ||
        empty($region) ||
        empty($accessKey) ||
        empty($secretKey)
    ) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Configuration Amazon S3 manquante"
        ]);

        exit;
    }


    // =====================================================
    // CONNEXION A AMAZON S3
    // =====================================================

    $s3 = new S3Client([
        'version' => 'latest',
        'region' => $region,
        'credentials' => [
            'key' => $accessKey,
            'secret' => $secretKey
        ]
    ]);


    // =====================================================
    // TRAITEMENT DES FICHIERS
    // =====================================================

    foreach ($_FILES as $value) {

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


            // =================================================
            // ENVOI VERS S3
            // =================================================

            try {

                // =================================================
                // TEST AVANT S3
                // =================================================

                echo json_encode([
                    'debug' => 'AVANT S3',
                    'bucket' => $bucket,
                    'region' => $region,
                    'key' => $keyName,
                    'tmpFile' => $tmpFile,
                    'exists' => file_exists($tmpFile)
                ]);

                exit;


                // =================================================
                // ENVOYER LE FICHIER DANS S3
                // =================================================

                $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $keyName,
                    'SourceFile' => $tmpFile,
                    'ContentType' => mime_content_type($tmpFile)
                ]);


                // =================================================
                // URL DE L'IMAGE
                // =================================================

                $url =
                    'https://' .
                    $bucket .
                    '.s3.' .
                    $region .
                    '.amazonaws.com/' .
                    $keyName;


                $back[] = $url;


            } catch (AwsException $e) {

                http_response_code(500);

                echo json_encode([
                    "success" => false,
                    "message" => "Erreur Amazon S3",
                    "error" => $e->getAwsErrorMessage()
                ]);

                exit;
            }
        }
    }


    return $back;
}


/**
 * Supprimer une image de S3
 */
function deleteFileFromS3(string $imageUrl): bool
{
    // =====================================================
    // CONFIGURATION AWS
    // =====================================================

    $bucket = $_ENV['AWS_BUCKET'] ?? getenv('AWS_BUCKET');
    $region = $_ENV['AWS_REGION'] ?? getenv('AWS_REGION');
    $accessKey = $_ENV['AWS_ACCESS_KEY_ID'] ?? getenv('AWS_ACCESS_KEY_ID');
    $secretKey = $_ENV['AWS_SECRET_ACCESS_KEY'] ?? getenv('AWS_SECRET_ACCESS_KEY');


    // =====================================================
    // VERIFIER CONFIGURATION
    // =====================================================

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

    $s3 = new S3Client([
        'version' => 'latest',
        'region' => $region,
        'credentials' => [
            'key' => $accessKey,
            'secret' => $secretKey
        ]
    ]);


    // =====================================================
    // RECUPERER LE CHEMIN DU FICHIER
    // =====================================================

    $path = parse_url($imageUrl, PHP_URL_PATH);

    if (!$path) {
        return false;
    }


    $key = ltrim($path, '/');


    // =====================================================
    // SUPPRESSION S3
    // =====================================================

    try {

        $s3->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key
        ]);

        return true;

    } catch (AwsException $e) {

        return false;
    }
}