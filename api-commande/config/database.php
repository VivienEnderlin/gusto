<?php
require __DIR__ . '/../../vendor/autoload.php';

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}
class Database {
    private static $servers = [];

    protected $pdo = null;
    private $initialized = false; // flag pour éviter réinitialisation

    public function __construct() {
        $this->connect();
    }

    // private function createDatabaseIfNotExists(array $srv){
    //     try {

    //         // Connexion au serveur MySQL (sans sélectionner de base)
    //         $pdo = new PDO(
    //             "mysql:host={$srv['host']};charset=utf8mb4",
    //             $srv['user'],
    //             $srv['password']
    //         );

    //         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //         $dbname = str_replace('`', '``', $srv['dbname']);

    //         $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`
    //                     CHARACTER SET utf8mb4
    //                     COLLATE utf8mb4_unicode_ci");

    //     } catch (PDOException $e) {
    //         throw $e;
    //     }
    // }

    public function connect() {

        self::$servers = [
            [
                // 'host' => $_ENV['JAWSDB_HOST'],
                // 'dbname' => $_ENV['JAWSDB_DATABASE'],
                // 'user' => $_ENV['JAWSDB_USERNAME'],
                // 'password' => $_ENV['JAWSDB_PASSWORD']
                
                'host' => getenv('JAWSDB_HOST'),
                'dbname' => getenv('JAWSDB_DATABASE'),
                'user' => getenv('JAWSDB_USERNAME'),
                'password' => getenv('JAWSDB_PASSWORD')
            ],
             // [
               
                
                
             // ]
        ];
    
        foreach (self::$servers as $srv) {
            try {
                //$this->createDatabaseIfNotExists($srv);
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
            die("Serveur de base de donnée indisponible !");
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

            // → Tables ici (copier ton tableau $tables actuel)
            $tables = [
                "etablissement" => "
                    CREATE TABLE IF NOT EXISTS etablissement (
                        id_etablissement INT AUTO_INCREMENT PRIMARY KEY,
                        logo TEXT NOT NULL,
                        nom VARCHAR(50) NOT NULL,
                        type VARCHAR(50) NOT NULL,
                        pays VARCHAR(50) NOT NULL,
                        devise VARCHAR(20) NOT NULL,
                        ville VARCHAR(50) NOT NULL,
                        adresse VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        telephone VARCHAR(50) NOT NULL,
                        country VARCHAR(10) NOT NULL,
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
                        code VARCHAR(50) NOT NULL,
                        date_validite DATE,
                        statu VARCHAR(20) NOT NULL,
                        role INT,
                        date_enreg DATE,
                        FOREIGN KEY (id_etablissement) REFERENCES etablissement(id_etablissement)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "tables_restaurant" => "
                    CREATE TABLE IF NOT EXISTS tables_restaurant (
                        id_table INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(20) NOT NULL,
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
                        devise VARCHAR(10) NOT NULL,
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
                        code VARCHAR(6) NOT NULL,
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
                        id_ticket TEXT,
                        id_etablissement INT,
                        id_table INT,
                        commande TEXT,
                        montant_total  VARCHAR(20) NOT NULL,
                        devise VARCHAR(10) NOT NULL,
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

            $this->seedEtablissement();
            $this->seedAdmin();
            

        } catch (PDOException $e) {
            die("Erreur initialisation DB : " . $e->getMessage());
        }
    }

    private function seedEtablissement()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM etablissement");
            
            if ($stmt->fetchColumn() == 0) {

                $stmt = $this->pdo->prepare("
                    INSERT INTO etablissement 
                    (logo, nom, type, pays, devise, ville, adresse, email, telephone, country, site_web, description, date_enreg)
                    VALUES
                    (:logo, :nom, :type, :pays, :devise, :ville, :adresse, :email, :telephone, :country, :site_web, :description, :date_enreg)
                ");

                $stmt->execute([
                    ':logo' => '',
                    ':nom' => 'Gusto',
                    ':type' => '',
                    ':pays' => 'Cameroun',
                    ':devise' => '*',
                    ':ville' => 'Yaoundé',
                    ':adresse' => '',
                    ':email' => '',
                    ':telephone' => '680468901',
                    ':country' => 'CM',
                    ':site_web' => '',
                    ':description' => '',
                    ':date_enreg' => date("Y-m-d")
                ]);
            }

        } catch (PDOException $e) {
            die("Erreur seed etablissement : " . $e->getMessage());
        }
    }

     private function seedAdmin()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = 'admin'");
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) {

                $stmt = $this->pdo->prepare("
                    INSERT INTO utilisateur 
                    (nom, adresse, email, telephone, login, password, id_etablissement, code, date_validite, statu, role, date_enreg)
                    VALUES
                    (:nom, :adresse, :email, :telephone, :login, :password, :id_etablissement, :code, :date_validite, :statu, :role, :date_enreg)
                ");

                $stmt->execute([
                    ':nom' => 'Djiomou Vivien',
                    ':adresse' => 'Yaoundé',
                    ':email' => 'djiomounandavivienenderlin@gmail.com',
                    ':telephone' => '657146124',
                    ':login' => 'admin',
                    ':password' => password_hash("admin", PASSWORD_DEFAULT),
                    ':id_etablissement' => 1,
                    ':code' => '0',
                    ':date_validite' => null,
                    ':statu' => 'En attente',
                    ':role' => 0,
                    ':date_enreg' => date("Y-m-d")
                ]);
            }

        } catch (PDOException $e) {
            die("Erreur seed admin : " . $e->getMessage());
        }
    }

    public function disconnect() {
        $this->pdo = null;
    }
}
?>