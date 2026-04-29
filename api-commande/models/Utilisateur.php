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

    function generateRestaurantCode(string $name, int $randomLength = 8): string
    {
        // Nettoyer le nom
        $prefix = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $name));
        $prefix = substr($prefix, 0, 10);

        // Partie aléatoire sécurisée
        $random = strtoupper(bin2hex(random_bytes(ceil($randomLength / 2))));
        $random = substr($random, 0, $randomLength);

        return $prefix . '-' . $random;
    }

    public function getAllUsers() {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE role != ?",
            [0]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_utilisateur = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEmployeByEtablissement($id_etablissement) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_etablissement = ?",
            [$id_etablissement]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID et établissement (sécurisé)
    // =========================
    public function getByIdAndEtablissement($id, $id_etablissement) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE id_categorie = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       CRUD
    ======================= */

    public function create($data) {

        // Génération automatique du mot de passe
        $password = $data['password'] ?? $this->generateRestaurantCode($data['nom']);
        $data['password'] = $password;

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
                "code",
                "date_validite",
                "statu",
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
                $data['id_etablissement'],
                "En attente",
                date('Y-m-d'),
                "En attente",
                $data['role'],
                date('Y-m-d'),
            ]
        );

        $id = $this->pdo->lastInsertId();


        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';   // à adapter
            $mail->SMTPAuth   = true;
            $mail->Username   = 'djiomounandavivienenderlin@gmail.com'; // à adapter
            $mail->Password   = 'vvzm ioaa gckv vcze ';       // à adapter
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('djiomounandavivienenderlin@gmail.com', 'Gusto');
            $mail->addAddress($data['email'], $data['nom']);

            $mail->isHTML(true);
            $mail->Subject = 'Login details for the Manager account';
            $mail->Body    = "
                <h3>Bonjour {$data['nom']},</h3>
                <p>Votre compte a été créé avec succès.</p>
                <p><strong>Login :</strong> {$data['login']}<br>
                   <strong>Password:</strong> {$password}</p>
                <p>You can change your password at any time</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error sending email to {$data['email']} : " . $mail->ErrorInfo);
        }

        return $id;
    }

    public function update($id, $data) {
        return $this->set(
            "utilisateur",
            ["nom", "adresse", "email", "telephone", "login", "role"],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                $data['role']
            ],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

    public function checkAndExpireContrats() {

        $today = date('Y-m-d');

        return $this->set(
            "utilisateur",
            ["statu"],
            ["Expiré"],
            "WHERE date_validite < ? AND statu != ?",
            [$today, "Expiré"]
        );
    }

    
    /* =======================
       AUTH
    ======================= */

    public function getByLogin($login) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE BINARY login = ?",
            [$login]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       Update login
    ======================= */

    public function updateLogin($id, $login, $id_etablissement, $password = null) {
    if ($password !== null && $password !== '') {
        return $this->set(
            "utilisateur",
            ["login", "password"],
            [
                $login,
                password_hash($password, PASSWORD_DEFAULT)
            ],
            "WHERE id_utilisateur = ? AND id_etablissement = ?",
            [$id, $id_etablissement]
        );
    }

    return $this->set(
        "utilisateur",
        ["login"],
        [$login],
        "WHERE id_utilisateur = ? AND id_etablissement = ?",
        [$id, $id_etablissement]
    );
}

}
