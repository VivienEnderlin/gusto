```php
<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;


/**
 * ============================================================
 * UPLOAD DES FICHIERS VERS AMAZON S3
 * ============================================================
 */
function uploadfile(array $typeFileAllowed, string $link = ''): array
{
    $back = [];

    // Aucun fichier envoyé
    if (empty($_FILES)) {
        return [];
    }


    // =========================================================
    // CONFIGURATION AWS
    // =========================================================

    $bucket = getenv('AWS_BUCKET');
    $region = getenv('AWS_REGION');
    $accessKey = getenv('AWS_ACCESS_KEY_ID');
    $secretKey = getenv('AWS_SECRET_ACCESS_KEY');


    // =========================================================
    // VÉRIFIER LA CONFIGURATION
    // =========================================================

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


    // =========================================================
    // IDENTIFIANTS AWS
    // =========================================================

    $credentials = new Credentials(
        $accessKey,
        $secretKey
    );


    // =========================================================
    // CLIENT AMAZON S3
    // =========================================================

    try {

        $s3 = new S3Client(
            $credentials,
            [
                'region' => $region
            ]
        );

    } catch (\Throwable $e) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Impossible de créer le client Amazon S3",
            "error" => $e->getMessage(),
            "type" => get_class($e)
        ]);

        exit;
    }


    // =========================================================
    // TRAITEMENT DES FICHIERS
    // =========================================================

    foreach ($_FILES as $value) {

        if (!isset($value['name'])) {
            continue;
        }


        // =====================================================
        // FICHIER UNIQUE
        // =====================================================

        if (!is_array($value['name'])) {

            $value['name'] = [$value['name']];
            $value['tmp_name'] = [$value['tmp_name']];
            $value['error'] = [$value['error']];
        }


        // =====================================================
        // PLUSIEURS FICHIERS
        // =====================================================

        foreach ($value['name'] as $key => $filename) {

            // Aucun fichier
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
                    "message" => "Erreur lors de l'upload du fichier",
                    "error_code" => $value['error'][$key] ?? null
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
                    "message" => "Type de fichier non supporté",
                    "extension" => $extension
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
            // CHEMIN DANS S3
            // =================================================

            $keyName = 'images/' . $newName;


            // =================================================
            // TYPE MIME
            // =================================================

            $mimeType = mime_content_type($tmpFile);

            if (!$mimeType) {
                $mimeType = 'application/octet-stream';
            }


            // =================================================
            // ENVOYER VERS AMAZON S3
            // =================================================

            try {

                $result = $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $keyName,
                    'SourceFile' => $tmpFile,
                    'ContentType' => $mimeType
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
                    "error" => $e->getAwsErrorMessage(),
                    "aws_code" => $e->getAwsErrorCode()
                ]);

                exit;

            } catch (\Throwable $e) {

                http_response_code(500);

                echo json_encode([
                    "success" => false,
                    "message" => "Erreur lors de l'envoi vers Amazon S3",
                    "error" => $e->getMessage(),
                    "type" => get_class($e)
                ]);

                exit;
            }
        }
    }


    // =========================================================
    // RETOURNER LES URLS
    // =========================================================

    return $back;
}


/**
 * ============================================================
 * SUPPRIMER UNE IMAGE DE S3
 * ============================================================
 */
function deleteFileFromS3(string $imageUrl): bool
{
    // =========================================================
    // CONFIGURATION AWS
    // =========================================================

    $bucket = getenv('AWS_BUCKET');
    $region = getenv('AWS_REGION');
    $accessKey = getenv('AWS_ACCESS_KEY_ID');
    $secretKey = getenv('AWS_SECRET_ACCESS_KEY');


    // =========================================================
    // VÉRIFIER LA CONFIGURATION
    // =========================================================

    if (
        empty($bucket) ||
        empty($region) ||
        empty($accessKey) ||
        empty($secretKey)
    ) {

        return false;
    }


    // =========================================================
    // IDENTIFIANTS AWS
    // =========================================================

    $credentials = new Credentials(
        $accessKey,
        $secretKey
    );


    // =========================================================
    // CLIENT S3
    // =========================================================

    try {

        $s3 = new S3Client(
            $credentials,
            [
                'region' => $region
            ]
        );

    } catch (\Throwable $e) {

        return false;
    }


    // =========================================================
    // RÉCUPÉRER LE CHEMIN DU FICHIER
    // =========================================================

    $path = parse_url($imageUrl, PHP_URL_PATH);


    if (!$path) {
        return false;
    }


    $key = ltrim($path, '/');


    // =========================================================
    // SUPPRESSION
    // =========================================================

    try {

        $s3->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key
        ]);

        return true;

    } catch (AwsException $e) {

        return false;

    } catch (\Throwable $e) {

        return false;
    }
}
```
