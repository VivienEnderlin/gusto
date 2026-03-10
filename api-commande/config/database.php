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
                    
                //admin

                "tables_restaurant" => "
                    CREATE TABLE IF NOT EXISTS tables_restaurant (
                        id_table INT AUTO_INCREMENT PRIMARY KEY,
                        Nom VARCHAR(10) NOT NULL,
                        id_etablissement INT,
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
                        prix INT,
                        description TEXT,
                        statu VARCHAR(50) NOT NULL,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                    //lorsque le serveur installe le client il ouvre le service sur la table toutes les commandes s'enregistrent dans l'objet service sauf date de fermeture. lui meme apres que le service soit fini il ferme le service et la date de fermeture marque la fin d'un servce. lorsque le client commande ca verifie le dernier id  de la table service si ca corresond avec l'id de sa table si il ya pas la date de fermeture il peu comande si y'en a ca bloque ou ca met une erreurdisant que que la table est en cours s'utilisation

                "service" => "
                    CREATE TABLE IF NOT EXISTS service (
                        id_service INT AUTO_INCREMENT PRIMARY KEY,
                        id_table INT,
                        id_etablissement INT,
                        date_heure_ouverture DATETIME,
                        date_heure_fermeture DATETIME,
                        staut VARCHAR(50) NOT NULL,
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",

                "item_commande" => "
                    CREATE TABLE IF NOT EXISTS item_commande (
                        id_item_commande INT AUTO_INCREMENT PRIMARY KEY,
                        id_etablissement INT,
                        id_table INT,
                        commande VARCHAR(50) NOT NULL,
                        quantite VARCHAR(2) NOT NULL,
                        prix VARCHAR(10) NOT NULL,
                        montant VARCHAR(20) NOT NULL,
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
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
                        date_heure  DATETIME,
                        statu  VARCHAR(20),
                        FOREIGN KEY (id_table) REFERENCES tables_restaurant(id_table)
                        ON DELETE CASCADE ON UPDATE CASCADE,
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
            die('Erreur PDO : '.$e->getMessage());
        }

        return $this->pdo;
    }

    public function disconnect() {
        $this->pdo = null;
    }
}
?>
