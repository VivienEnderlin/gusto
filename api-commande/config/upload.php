<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

use Aws\S3\S3Client;


/**
 * Upload des fichiers vers Amazon S3
 */
function uploadfile(array $typeFileAllowed, string $link = ''): array
{
    $back = [];

    // =====================================================
    // VERIFIER $_FILES
    // =====================================================

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


    if (
        empty($bucket) ||
        empty($region) ||
        empty($accessKey) ||
        empty($secretKey)
    ) {

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => 'Configuration Amazon S3 manquante'
        ]);

        exit;
    }


    // =====================================================
    // CREATION CLIENT S3
    // =====================================================

    try {

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $accessKey,
                'secret' => $secretKey
            ]
        ]);

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => 'Impossible de créer le client Amazon S3',
            'error' => $e->getMessage(),
            'type' => get_class($e)
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
            // ERREUR UPLOAD
            // =================================================

            $uploadError = $value['error'][$key] ?? UPLOAD_ERR_NO_FILE;

            if ($uploadError !== UPLOAD_ERR_OK) {

                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload du fichier',
                    'upload_error' => $uploadError
                ]);

                exit;
            }


            // =================================================
            // FICHIER TEMPORAIRE
            // =================================================

            $tmpFile = $value['tmp_name'][$key] ?? '';

            if (!$tmpFile || !file_exists($tmpFile)) {

                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'message' => 'Fichier temporaire introuvable'
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
                    'success' => false,
                    'message' => 'Type de fichier non supporté',
                    'extension' => $extension
                ]);

                exit;
            }


            // =================================================
            // HASH
            // =================================================

            $hash = sha1_file($tmpFile);

            if ($hash === false) {

                http_response_code(500);

                echo json_encode([
                    'success' => false,
                    'message' => 'Impossible de calculer le hash du fichier'
                ]);

                exit;
            }


            // =================================================
            // NOM FICHIER
            // =================================================

            $newName = $hash . '.' . $extension;


            // =================================================
            // CHEMIN S3
            // =================================================

            $keyName = 'images/' . $newName;


            // =================================================
            // TYPE MIME
            // =================================================

            try {

                $contentType = mime_content_type($tmpFile);

            } catch (Throwable $e) {

                http_response_code(500);

                echo json_encode([
                    'success' => false,
                    'message' => 'Impossible de déterminer le type MIME',
                    'error' => $e->getMessage(),
                    'type' => get_class($e)
                ]);

                exit;
            }


            // =================================================
            // UPLOAD S3
            // =================================================

            try {

                $result = $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $keyName,
                    'SourceFile' => $tmpFile,
                    'ContentType' => $contentType
                ]);


                // =================================================
                // URL
                // =================================================

                $url =
                    'https://' .
                    $bucket .
                    '.s3.' .
                    $region .
                    '.amazonaws.com/' .
                    $keyName;


                $back[] = $url;


            } catch (Throwable $e) {

                http_response_code(500);

                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur Amazon S3',
                    'error' => $e->getMessage(),
                    'type' => get_class($e),
                    'bucket' => $bucket,
                    'region' => $region,
                    'key' => $keyName
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
    $bucket = $_ENV['AWS_BUCKET'] ?? getenv('AWS_BUCKET');
    $region = $_ENV['AWS_REGION'] ?? getenv('AWS_REGION');
    $accessKey = $_ENV['AWS_ACCESS_KEY_ID'] ?? getenv('AWS_ACCESS_KEY_ID');
    $secretKey = $_ENV['AWS_SECRET_ACCESS_KEY'] ?? getenv('AWS_SECRET_ACCESS_KEY');


    if (
        empty($bucket) ||
        empty($region) ||
        empty($accessKey) ||
        empty($secretKey)
    ) {
        return false;
    }


    try {

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $accessKey,
                'secret' => $secretKey
            ]
        ]);


        $path = parse_url($imageUrl, PHP_URL_PATH);

        if (!$path) {
            return false;
        }


        $key = ltrim($path, '/');


        $s3->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key
        ]);


        return true;

    } catch (Throwable $e) {

        return false;
    }
}