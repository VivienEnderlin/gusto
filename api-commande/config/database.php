<?php
class Database {

    private static $host = 'localhost';
    private static $name = 'etablissement';
    private static $user = 'root';
    private static $password = '';
    protected $pdo = null;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . self::$host . ';charset=utf8',
                self::$user,
                self::$password,
                [PDO::ATTR_PERSISTENT => true]
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Créer la base si nécessaire
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `" . self::$name . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->pdo->exec("USE `" . self::$name . "`");

            // Tables
            $tables = [
                "etablissement" => "
                    CREATE TABLE IF NOT EXISTS etablissement (
                        id_etablissement INT AUTO_INCREMENT PRIMARY KEY,
                        id_unique_etablissement VARCHAR(100) NOT NULL UNIQUE,
                        logo TEXT NOT NULL,
                        nom VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        site_web VARCHAR(50) NOT NULL,
                        description TEXT NOT NULL,
                        dateenreg DATE
                    )",

                "appareil" => "
                    CREATE TABLE IF NOT EXISTS appareil (
                        id_appareil INT AUTO_INCREMENT PRIMARY KEY,
                        id_unique_etablissement VARCHAR(100) NOT NULL,
                        marque VARCHAR(50) NOT NULL,
                        model VARCHAR(50) NOT NULL,
                        numero_serie VARCHAR(50) NOT NULL,
                        systeme_exploitation VARCHAR(50) NOT NULL,
                        annee_fabrication VARCHAR(50) NOT NULL,
                        date_fin_support DATE,
                        description TEXT NOT NULL,
                        FOREIGN KEY (id_unique_etablissement) REFERENCES etablissement(id_unique_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "licence" => "
                    CREATE TABLE IF NOT EXISTS licence (
                        id_licence INT AUTO_INCREMENT PRIMARY KEY,
                        id_appareil INT,
                        id_unique_etablissement VARCHAR(100) NOT NULL,
                        code VARCHAR(50) NOT NULL,
                        date_validite DATE,
                        FOREIGN KEY (id_unique_etablissement) REFERENCES etablissement(id_unique_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_appareil) REFERENCES appareil(id_appareil)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "utilisateur" => "
                    CREATE TABLE IF NOT EXISTS utilisateur (
                        id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(50) NOT NULL,
                        prenom VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        telephone VARCHAR(20) NOT NULL,
                        login VARCHAR(50) NOT NULL,
                        id_unique_etablissement VARCHAR(100) NOT NULL,
                        role VARCHAR(10) NOT NULL,
                        date_enreg DATE,
                        FOREIGN KEY (id_unique_etablissement) REFERENCES etablissement(id_unique_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "tables_restaurant" => "
                    CREATE TABLE IF NOT EXISTS tables_restaurant (
                        id_table INT AUTO_INCREMENT PRIMARY KEY,
                        id_unique_table VARCHAR(10) NOT NULL,
                        id_unique_etablissement VARCHAR(100) NOT NULL,
                        id_utilisateur INT,
                        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_unique_etablissement) REFERENCES etablissement(id_unique_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "categorie" => "
                    CREATE TABLE IF NOT EXISTS categorie (
                        idcategorie INT AUTO_INCREMENT PRIMARY KEY,
                        libelle VARCHAR(50) NOT NULL
                    )",

                "produit" => "
                    CREATE TABLE IF NOT EXISTS produit (
                        id_produit INT AUTO_INCREMENT PRIMARY KEY,
                        id_unique_etablissement VARCHAR(100) NOT NULL,
                        nom VARCHAR(50) NOT NULL,
                        image TEXT NOT NULL,
                        idcategorie INT,
                        prix INT NOT NULL,
                        description TEXT NOT NULL,
                        FOREIGN KEY (id_unique_etablissement) REFERENCES etablissement(id_unique_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (idcategorie) REFERENCES categorie(idcategorie)
                        ON DELETE SET NULL
                    )",

                "service" => "
                    CREATE TABLE IF NOT EXISTS service (
                        id_service INT AUTO_INCREMENT PRIMARY KEY,
                        id_table INT,
                        date_heure_ouverture DATETIME,
                        date_heure_fermeture DATETIME,
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "commande" => "
                    CREATE TABLE IF NOT EXISTS commande (
                        id_commande INT AUTO_INCREMENT PRIMARY KEY,
                        id_unique_etablissement VARCHAR(100) NOT NULL,
                        commande TEXT,
                        montant_a_payer VARCHAR(50) NOT NULL,
                        date_jour DATETIME,
                        etat VARCHAR(50) NOT NULL,
                        FOREIGN KEY (id_unique_etablissement) REFERENCES etablissement(id_unique_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )"
            ];

            foreach ($tables as $sql) {
                $this->pdo->exec($sql);
            }

            // Créer utilisateur admin si absent
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM utilisateur");
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO utilisateur 
                    (nom, prenom, adresse, email, telephone, login, id_unique_etablissement, role, date_enreg) 
                    VALUES (:nom, :prenom, :adresse, :email, :telephone, :login, :id_unique_etablissement, :role, :date_enreg)
                ");
                $stmt->execute([
                    ':nom' => '',
                    ':prenom' => '',
                    ':adresse' => '',
                    ':email' => '',
                    ':telephone' => '',
                    ':login' => 'admin',
                    ':id_unique_etablissement' => hash('sha256','admin'),
                    ':role' => 'admin',
                    ':date_enreg' => date("Y-m-d")
                ]);
            }

            // Catégories par défaut
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM categorie");
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->pdo->prepare("INSERT INTO categorie (libelle) VALUES (:libelle)");
                foreach (['Plat','Boisson','Dessert'] as $libelle) {
                    $stmt->execute([':libelle' => $libelle]);
                }
            }

        } catch (PDOException $e) {
            die('Erreur PDO : '.$e->getMessage());
        }

        return $this->pdo;
    }

    public function disconnect() {
        $this->pdo = null;
    }
}
?>
