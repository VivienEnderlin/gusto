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



// require_once __DIR__ . '/../vendor/autoload.php';

// use Aws\S3\S3Client;
// use Aws\Exception\AwsException;

// /**
//  * Upload des fichiers vers Amazon S3
//  *
//  * @param array $typeFileAllowed Extensions autorisées
//  * @return array URLs des fichiers uploadés
//  */
// function uploadfile(array $typeFileAllowed): array
// {
//     $back = [];

//     if (empty($_FILES)) {
//         return [];
//     }

//     // Configuration AWS depuis les variables d'environnement
//     $bucket = getenv('AWS_BUCKET');
//     $region = getenv('AWS_REGION');
//     $accessKey = getenv('AWS_ACCESS_KEY_ID');
//     $secretKey = getenv('AWS_SECRET_ACCESS_KEY');

//     if (!$bucket || !$region || !$accessKey || !$secretKey) {
//         http_response_code(500);

//         echo json_encode([
//             "success" => false,
//             "message" => "Configuration Amazon S3 manquante"
//         ]);

//         exit;
//     }

//     // Client S3
//     $s3 = new S3Client([
//         'version' => 'latest',
//         'region'  => $region,
//         'credentials' => [
//             'key'    => $accessKey,
//             'secret' => $secretKey
//         ]
//     ]);

//     foreach ($_FILES as $value) {

//         if (!isset($value['name'])) {
//             continue;
//         }

//         // Fichier unique
//         if (!is_array($value['name'])) {
//             $value['name'] = [$value['name']];
//             $value['tmp_name'] = [$value['tmp_name']];
//             $value['error'] = [$value['error']];
//         }

//         foreach ($value['name'] as $key => $filename) {

//             if (!$filename) {
//                 continue;
//             }

//             // Vérifier erreur upload
//             if (
//                 !isset($value['error'][$key]) ||
//                 $value['error'][$key] !== UPLOAD_ERR_OK
//             ) {
//                 http_response_code(400);

//                 echo json_encode([
//                     "success" => false,
//                     "message" => "Erreur lors de l'upload du fichier"
//                 ]);

//                 exit;
//             }

//             $tmpFile = $value['tmp_name'][$key];

//             // Extension
//             $extension = strtolower(
//                 pathinfo($filename, PATHINFO_EXTENSION)
//             );

//             if (!in_array($extension, $typeFileAllowed, true)) {

//                 http_response_code(400);

//                 echo json_encode([
//                     "success" => false,
//                     "message" => "Type de fichier non supporté"
//                 ]);

//                 exit;
//             }

//             // Vérifier que le fichier existe
//             if (!file_exists($tmpFile)) {

//                 http_response_code(400);

//                 echo json_encode([
//                     "success" => false,
//                     "message" => "Fichier temporaire introuvable"
//                 ]);

//                 exit;
//             }

//             // Hash unique
//             $hash = sha1_file($tmpFile);

//             if ($hash === false) {

//                 http_response_code(500);

//                 echo json_encode([
//                     "success" => false,
//                     "message" => "Impossible de calculer le hash du fichier"
//                 ]);

//                 exit;
//             }

//             $newName = $hash . '.' . $extension;

//             // Chemin dans S3
//             $keyName = 'images/' . $newName;

//             try {

//                 // Vérifier si le fichier existe déjà
//                 try {

//                     $s3->headObject([
//                         'Bucket' => $bucket,
//                         'Key'    => $keyName
//                     ]);

//                     // Le fichier existe déjà
//                     $exists = true;

//                 } catch (AwsException $e) {

//                     $exists = false;
//                 }

//                 // Upload uniquement si absent
//                 if (!$exists) {

//                     $result = $s3->putObject([
//                         'Bucket' => $bucket,
//                         'Key'    => $keyName,
//                         'SourceFile' => $tmpFile,
//                         'ContentType' => mime_content_type($tmpFile)
//                     ]);
//                 }

//                 // URL publique
//                 $url = 'https://' .
//                     $bucket .
//                     '.s3.' .
//                     $region .
//                     '.amazonaws.com/' .
//                     $keyName;

//                 $back[] = $url;

//             } catch (AwsException $e) {

//                 http_response_code(500);

//                 echo json_encode([
//                     "success" => false,
//                     "message" => "Erreur Amazon S3",
//                     "error" => $e->getAwsErrorMessage()
//                 ]);

//                 exit;
//             }
//         }
//     }

//     return $back;
// }


// /**
//  * Supprimer une image de S3
//  */
// function deleteFileFromS3(string $imageUrl): bool
// {
//     $bucket = getenv('AWS_BUCKET');
//     $region = getenv('AWS_REGION');
//     $accessKey = getenv('AWS_ACCESS_KEY_ID');
//     $secretKey = getenv('AWS_SECRET_ACCESS_KEY');

//     if (!$bucket || !$region || !$accessKey || !$secretKey) {
//         return false;
//     }

//     $s3 = new S3Client([
//         'version' => 'latest',
//         'region'  => $region,
//         'credentials' => [
//             'key'    => $accessKey,
//             'secret' => $secretKey
//         ]
//     ]);

//     // Extraire le nom du fichier depuis l'URL
//     $path = parse_url($imageUrl, PHP_URL_PATH);

//     if (!$path) {
//         return false;
//     }

//     $key = ltrim($path, '/');

//     try {

//         $s3->deleteObject([
//             'Bucket' => $bucket,
//             'Key'    => $key
//         ]);

//         return true;

//     } catch (AwsException $e) {

//         return false;
//     }
// }