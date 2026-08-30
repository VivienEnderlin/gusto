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

    $bucket = getenv('AWS_BUCKET');
    $region = getenv('AWS_REGION');
    $accessKey = getenv('AWS_ACCESS_KEY_ID');
    $secretKey = getenv('AWS_SECRET_ACCESS_KEY');

    // Vérifier la configuration
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


        // Fichier unique
        if (!is_array($value['name'])) {

            $value['name'] = [$value['name']];
            $value['tmp_name'] = [$value['tmp_name']];
            $value['error'] = [$value['error']];
        }


        // Plusieurs fichiers
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


            // Nom du fichier
            $newName = $hash . '.' . $extension;


            // =================================================
            // CHEMIN DANS LE BUCKET
            // =================================================

            $keyName = 'images/' . $newName;


            try {

    error_log("1 - AVANT S3");

    $s3->putObject([
        'Bucket' => $bucket,
        'Key' => $keyName,
        'SourceFile' => $tmpFile,
        'ContentType' => mime_content_type($tmpFile)
    ]);

    error_log("2 - APRES S3");

    $url =
        'https://' .
        $bucket .
        '.s3.' .
        $region .
        '.amazonaws.com/' .
        $keyName;

    $back[] = $url;

} catch (\Throwable $e) {

    error_log("ERREUR S3 : " . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Erreur pendant upload',
        'error' => $e->getMessage()
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