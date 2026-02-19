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
                        logo TEXT NOT NULL,
                        nom VARCHAR(50) NOT NULL,
                        type VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        telephone VARCHAR(50) NOT NULL,
                        site_web VARCHAR(50) NOT NULL,
                        description TEXT NOT NULL,
                        date_enreg DATE,
                        statu varchar(20)
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

                "licence" => "
                    CREATE TABLE IF NOT EXISTS licence (
                        id_licence INT AUTO_INCREMENT PRIMARY KEY,
                        id_appareil INT,
                        id_etablissement INT,
                        code VARCHAR(50) NOT NULL,
                        date_validite DATE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_appareil) REFERENCES appareil(id_appareil)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "utilisateur" => "
                    CREATE TABLE IF NOT EXISTS utilisateur (
                        id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        telephone VARCHAR(20) NOT NULL,
                        login VARCHAR(50) NOT NULL,
                        password TEXT,
                        id_etablissement INT,
                        role INT,
                        date_enreg DATE,
                        statu varchar(20),
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "tables_restaurant" => "
                    CREATE TABLE IF NOT EXISTS tables_restaurant (
                        id_table INT AUTO_INCREMENT PRIMARY KEY,
                        id_unique_table VARCHAR(10) NOT NULL,
                        id_etablissement INT,
                        id_utilisateur INT,
                        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "categorie" => "
                    CREATE TABLE IF NOT EXISTS categorie (
                        idcategorie INT AUTO_INCREMENT PRIMARY KEY,
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
                        idcategorie INT,
                        prix INT,
                        description TEXT,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (idcategorie) REFERENCES categorie(idcategorie)
                        ON DELETE SET NULL
                    )",

                    //lorsque le serveur installe le clien il ouvre la commande ces element s'enregistrent dans la table service sauf date de fermeture. lui meme apres que le service soit fini il ferme le service et la date de fermeture marque la fin d'un servce. lorsque le clien commande ca verifie le dernier id  de la table service si ca corresond avec l'id de sa table si il ya pas la date de fermeture il peu comande si y'en a ca bloc ca bloque ou alors ca alerte (serveur gerant) pour voir si c'est pas un individu qui derange ou pas

                "service" => "
                    CREATE TABLE IF NOT EXISTS service (
                        id_service INT AUTO_INCREMENT PRIMARY KEY,
                        id_table INT,
                        id_etablissement INT,
                        date_heure_ouverture DATETIME,
                        date_heure_fermeture DATETIME,
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "commande" => "
                    CREATE TABLE IF NOT EXISTS commande (
                        id_commande INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        commande TEXT,
                        montant_a_payer VARCHAR(50) NOT NULL,
                        date_jour DATETIME,
                        etat VARCHAR(50) NOT NULL,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
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
                    (nom, adresse, email, telephone, login, password, id_etablissement, role, date_enreg) 
                    VALUES (:nom, :adresse, :email, :telephone, :login, :password, :id_etablissement, :role, :date_enreg)
                ");

                $stmt->execute([
                    ':nom' => '',
                    ':adresse' => '',
                    ':email' => '',
                    ':telephone' => '',
                    ':login' => 'admin',
                    ':id_etablissement' => 0,
                    ':password' => password_hash("admin", PASSWORD_DEFAULT),
                    ':role' => 0,
                    ':date_enreg' => date("Y-m-d")
                ]);
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
