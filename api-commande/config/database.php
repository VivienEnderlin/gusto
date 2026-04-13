<?php
class Database {
    private static $servers = [
        [
            'host' => 'localhost',
            'dbname' => 'etablissement',
            'user' => 'root',
            'password' => ''
        ],
        // [
        //     'host' => '192.168.1.11',
        //     'dbname' => 'etablissement',
        //     'user' => 'root',
        //     'password' => ''
        // ]
    ];

    protected $pdo = null;
    private $initialized = false; // flag pour éviter réinitialisation

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        foreach (self::$servers as $srv) {
            try {
                $this->pdo = new PDO(
                    "mysql:host={$srv['host']};dbname={$srv['dbname']};charset=utf8mb4",
                    $srv['user'],
                    $srv['password'],
                    [PDO::ATTR_PERSISTENT => true]
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                error_log("Connecté à la DB sur {$srv['host']}");
                break;
            } catch (PDOException $e) {
                error_log("Impossible de se connecter à {$srv['host']}: " . $e->getMessage());
            }
        }

        if (!$this->pdo) {
            die("Tous les serveurs de base de données sont indisponibles !");
        }

        // ✅ Initialisation uniquement si pas déjà fait
        $lockFile = __DIR__ . '/db_initialized.lock';
        if (!$this->initialized && !file_exists($lockFile)) {
            $this->initDatabase();
            file_put_contents($lockFile, date('c')); // créer le lock
            $this->initialized = true;
        }

        return $this->pdo;
    }

    private function initDatabase() {
        try {
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS etablissement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->pdo->exec("USE etablissement");

            // → Tables ici (copier ton tableau $tables actuel)
            $tables = [
                "etablissement" => "
                    CREATE TABLE IF NOT EXISTS etablissement (
                        id_etablissement INT AUTO_INCREMENT PRIMARY KEY,
                        logo TEXT NOT NULL,
                        nom VARCHAR(50) NOT NULL,
                        type VARCHAR(50) NOT NULL,
                        pays VARCHAR(50) NOT NULL,
                        ville VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        telephone VARCHAR(50) NOT NULL,
                        site_web VARCHAR(50) NOT NULL,
                        description TEXT NOT NULL,
                        date_enreg DATE
                    )",
                "appareil" => "
                    CREATE TABLE IF NOT EXISTS appareil (
                        id_appareil INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        marque VARCHAR(50) NOT NULL,
                        model VARCHAR(50) NOT NULL,
                        numero_serie VARCHAR(50) NOT NULL,
                        systeme_exploitation VARCHAR(50) NOT NULL,
                        annee_fabrication VARCHAR(50) NOT NULL,
                        date_fin_support DATE,
                        description TEXT,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "contrat" => "
                    CREATE TABLE IF NOT EXISTS contrat (
                        id_contrat INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        code VARCHAR(50) NOT NULL,
                        date_validite DATE,
                        description TEXT,
                        statu VARCHAR(20) NOT NULL,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "utilisateur" => "
                    CREATE TABLE IF NOT EXISTS utilisateur (
                        id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        telephone VARCHAR(50) NOT NULL,
                        login VARCHAR(50) NOT NULL,
                        password TEXT,
                        id_etablissement INT,
                        role INT,
                        date_enreg DATE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "tables_restaurant" => "
                    CREATE TABLE IF NOT EXISTS tables_restaurant (
                        id_table INT AUTO_INCREMENT PRIMARY KEY,
                        Nom VARCHAR(20) NOT NULL,
                        id_etablissement INT,
                        statu VARCHAR(10) NOT NULL,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "categorie" => "
                    CREATE TABLE IF NOT EXISTS categorie (
                        id_categorie INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        libelle VARCHAR(50) NOT NULL,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "produit" => "
                    CREATE TABLE IF NOT EXISTS produit (
                        id_produit INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        nom VARCHAR(50) NOT NULL,
                        image TEXT,
                        id_categorie INT,
                        prix VARCHAR(11) NOT NULL,
                        description TEXT,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "service" => "
                    CREATE TABLE IF NOT EXISTS service (
                        id_service INT AUTO_INCREMENT PRIMARY KEY,
                        id_table INT,
                        id_utilisateur INT,
                        id_etablissement INT,
                        date_heure_ouverture DATETIME,
                        date_heure_fermeture DATETIME,
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "commande" => "
                    CREATE TABLE IF NOT EXISTS commande (
                        id_commande INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        id_table INT,
                        commande TEXT,
                        montant_total  VARCHAR(20) NOT NULL,
                        etat  VARCHAR(20) NOT NULL,
                        date_enreg  DATETIME,
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )"
            ];

            foreach ($tables as $sql) {
                $this->pdo->exec($sql);
            }

            // Création de l'admin si aucun utilisateur
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM utilisateur");
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO utilisateur 
                    (nom, adresse, email, telephone, login, password, id_etablissement, role, date_enreg) 
                    VALUES (:nom, :adresse, :email, :telephone, :login, :password, :id_etablissement, :role, :date_enreg)
                ");
                $stmt->execute([
                    ':nom' => 'Djiomou Vivien',
                    ':adresse' => 'Yaoundé-Cameroun',
                    ':email' => 'admin@gmail.com',
                    ':telephone' => '680468901',
                    ':login' => 'admin',
                    ':id_etablissement' => 0,
                    ':password' => password_hash("admin", PASSWORD_DEFAULT),
                    ':role' => 0,
                    ':date_enreg' => date("Y-m-d")
                ]);
            }

        } catch (PDOException $e) {
            die("Erreur initialisation DB : " . $e->getMessage());
        }
    }

    public function disconnect() {
        $this->pdo = null;
    }
}
?>