<?php
require_once __DIR__ . '/BaseModel.php';

require_once __DIR__ . '/../utils/phpmailer/src/Exception.php';
require_once __DIR__ . '/../utils/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../utils/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Utilisateur extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    function generateRestaurantCode(string $name, int $randomLength = 3): string
    {
        // Nettoyer le nom
        $prefix = strtolower(preg_replace('/[^A-Z0-9]/i', '', $name));
        $prefix = substr($prefix, 0, 2);

        // Partie aléatoire sécurisée
        $random = strtolower(bin2hex(random_bytes(ceil($randomLength / 2))));
        $random = substr($random, 0, $randomLength);

        return $prefix . '-' . $random;
    }

    public function getEmployeByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_etablissement = ? AND role = ?",
            [$id_etablissement, 2]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByInfoAndEtablissement($login, $telephone, $email, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE login = ? AND telephone = ? AND email = ? AND id_etablissement = ?",
            [$login, $telephone, $email, $id_etablissement]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID et établissement (sécurisé)
    // =========================
    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_utilisateur = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       CRUD
    ======================= */

    public function create($data, $id_etablissement) {

        // Génération automatique du mot de passe
        $password = $data['password'] ?? $this->generateRestaurantCode($data['nom']);
        $data['password'] = $password;

        $lodin = trim($data['lodin']);
        $telephone = trim($data['telephone']);
        $email = trim($data['email']);

        // Vérifier si le libelle existe déjà dans cet établissement
        $existing = $this->getByInfoAndEtablissement($login, $telephone, $email, $id_etablissement);

        if ($existing) {
            return false;
        }

        // Insertion en base
        $this->insert(
            "utilisateur",
            [
                "nom",
                "adresse",
                "email",
                "telephone",
                "login",
                "password",
                "id_etablissement",
                "role",
                "date_enreg",
            ],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                password_hash($password, PASSWORD_DEFAULT),
                $id_etablissement,
                2,
                gmdate('Y-m-d'),
            ]
        );

        $id = $this->pdo->lastInsertId();


        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'djiomounandavivienenderlin@gmail.com';
            $mail->Password   = 'dhtc ixqe gqxz hqxy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('djiomounandavivienenderlin@gmail.com', 'Gusto');
            $mail->addAddress($data['email'], $data['nom']);

            $mail->isHTML(true);
            $mail->Subject = 'Login details for the waiter account';
            $mail->Body    = "
                <h3>Bonjour {$data['nom']},</h3>
                <p>Votre compte a été créé avec succès.</p>
                <p><strong>Login :</strong> {$data['login']}<br>
                   <strong>Password :</strong> {$password}</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error sending email to {$data['email']} : " . $mail->ErrorInfo);
        }

        return $id;
    }

    public function update($id, $id_etablissement, $data) {
        return $this->set(
            "utilisateur",
            ["nom", "adresse", "email", "telephone", "login", "role"],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                2
            ],
            "WHERE id_utilisateur = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    // =========================
    // Supprimer  (sécurisé par établissement)
    // =========================
    public function delete($id, $id_etablissement){
        return $this->personalDelete(
            "utilisateur",
            "WHERE id_utilisateur = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }
    

}
