<?php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/../utils/phpmailer/src/Exception.php';
require_once __DIR__ . '/../utils/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../utils/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contrat extends BaseModel {

    private function generateLicenceCode($idEtablissement, $dateValidite) {
        // 1️⃣ Récupérer le nom de l'établissement
        $stmt = $this->personnalSelect(
            "etablissement",
            "nom",
            "WHERE id_etablissement = ?",
            [$idEtablissement]
        );
        $etablissement = $stmt->fetch(PDO::FETCH_ASSOC);
        $nomEtablissement = $etablissement ? preg_replace('/\s+/', '', strtoupper($etablissement['nom'])) : "UNKNOWN";

        // 2️⃣ Formater les dates
        $dateCreation = date('Ymd');
        $dateValidite = date('Ymd', strtotime($dateValidite));

        // 3️⃣ Partie aléatoire
        $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));

        // 4️⃣ Retourner le code licence
        return "{$nomEtablissement}-{$dateCreation}-{$dateValidite}-{$random}";
    }
    
    // Récupérer tous les établissements
    public function getAllContrats() {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE role != ?",
            [0]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilsateur par ID
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_utilisateur = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un établissement
    public function update($id, $data){

        $licence = $data['code'] ?? $this->generateLicenceCode($data['id_etablissement'],$data['date_validite']);

        $result = $this->set(
            "utilisateur",
            ["code", "date_validite", "statu"],
            [
                $licence,
                $data['date_validite'],
                "Valide"
            ],
            "WHERE id_utilisateur = ?",
            [$id]
        );

        if ($result) {
            $user = $this->getById($id);
            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'djiomounandavivienenderlin@gmail.com';
                $mail->Password   = 'vvzm ioaa gckv vcze ';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('djiomounandavivienenderlin@gmail.com', 'Gusto');
                $mail->addAddress($user['email'], $user['nom']);

                $mail->isHTML(true);
                $mail->Subject = 'Subscription renewal';
                $mail->Body = "
                    <h3>Hello {$user['nom']},</h3>
                    <p>Your subscription has been successfully renewed.</p>
                    <p>It will expire on <strong>{$data['date_validite']}</strong>.</p>
                ";

                $mail->send();

            } catch (Exception $e) {
                error_log(
                    "Error sending email to {$user['email']}: "
                    . $mail->ErrorInfo
                );
            }
        }

        return $result;
    }
}
?>
