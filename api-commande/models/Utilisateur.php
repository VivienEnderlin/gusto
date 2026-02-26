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

    /* =======================
       CRUD
    ======================= */

    public function create($data) {

        $data['statu'] = $data['statu'] ?? 'Activer';

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
                "role",
                "date_enreg",
                "statu"
            ],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                password_hash($password, PASSWORD_DEFAULT),
                $data['id_etablissement'],
                $data['role'],
                date('Y-m-d'),
                $data['statu']
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
            $mail->Subject = 'Information de connexion au compte Gérant';
            $mail->Body    = "
                <h3>Bonjour {$data['nom']},</h3>
                <p>Votre compte a été créé avec succès.</p>
                <p><strong>Login :</strong> {$data['login']}<br>
                   <strong>Mot de passe :</strong> {$password}</p>
                <p>Vous pouvez modifier votre mot de passe à tout moment</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erreur envoi mail à {$data['email']} : " . $mail->ErrorInfo);
        }

        return $id;
    }

    public function update($id, $data) {
        return $this->set(
            "utilisateur",
            [
                "nom",
                "adresse",
                "email",
                "telephone",
                "login",
                "id_etablissement",
                "role"
                
            ],
            [
                $data['nom'],
                $data['adresse'],
                $data['email'],
                $data['telephone'],
                $data['login'],
                $data['id_etablissement'],
                $data['role']
            ],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

    //change statut

    public function toggleStatut($id) {
        $e = $this->getById($id);
        if (!$e) return false;

        if ($e['statu'] === 'Activer') {
            // Désactivation → on ne change QUE le statut
            return $this->set(
                "utilisateur",
                ["statu"],
                ["Bloquer"],
                "WHERE id_utilisateur = ?",
                [$id]
            );
        } else {
            // Activation → on change le statut ET la date
            return $this->set(
                "utilisateur",
                ["statu", "date_enreg"],
                ["Activer", date('Y-m-d')],
                "WHERE id_utilisateur = ?",
                [$id]
            );
        }
    }

    /* =======================
       AUTH
    ======================= */

    public function getByLogin($login) {
        $stmt = $this->personnalSelect(
            "utilisateur",
            "*",
            "WHERE login = ?",
            [$login]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       Update login
    ======================= */

    public function updateLogin($id, $login, $password = null) {
        if ($password !== null && $password !== '') {
            return $this->set(
                "utilisateur",
                ["login", "password"],
                [
                    $login,
                    password_hash($password, PASSWORD_DEFAULT)
                ],
                "WHERE id_utilisateur = ?",
                [$id]
            );
        }

        return $this->set(
            "utilisateur",
            ["login"],
            [$login],
            "WHERE id_utilisateur = ?",
            [$id]
        );
    }

}
