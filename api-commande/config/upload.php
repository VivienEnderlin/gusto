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
    // CONNEXION À AMAZON S3
    // =====================================================

    try {

        $s3 = S3Client::factory([
            'key'    => $accessKey,
            'secret' => $secretKey,
            'region' => $region
        ]);

    } catch (\Throwable $e) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Erreur lors de la création du client S3",
            "error" => $e->getMessage()
        ]);

        exit;
    }

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
            // VÉRIFIER ERREUR UPLOAD
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
            // VÉRIFIER FICHIER
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

            // =================================================
            // ENVOI VERS S3
            // =================================================

            try {

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
    // RÉCUPÉRER LE CHEMIN DU FICHIER
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
