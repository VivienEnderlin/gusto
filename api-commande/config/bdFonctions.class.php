<?php
ini_set('max_execution_time', 21600);

require_once 'database.php';

class bdFonctions extends Database
{
    function __construct()
    {
        parent::__construct();
    }

    // IMPORTANT : Valider table & colonnes (ex: vérifier qu'ils ne contiennent que des caractères autorisés)
    private function validateName($name) {
        if (preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            return $name;
        }
        throw new Exception("Nom invalide: $name");
    }

    // Pour plusieurs colonnes (tableau)
    private function validateColumns(array $cols) {
        foreach ($cols as $col) {
            $this->validateName($col);
        }
        return $cols;
    }

    /**
     * getAll sécurisé
     * $condi doit contenir uniquement des placeholders (?) et $params valeurs associées
     */
    public function getAll($tableName, $condi = '', $params = [])
    {
        $tableName = $this->validateName($tableName);

        $sql = 'SELECT * FROM ' . $tableName;
        if ($condi) {
            $sql .= ' ' . $condi;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * personnalSelect sécurisé
     * $colSelect = string avec noms de colonnes séparés par virgule (ex: "id, name")
     * $condi = condition avec placeholders
     * $params = valeurs à binder
     */
    public function personnalSelect($tableName, $colSelect, $condi = '', $params = [])
    {
        $tableName = $this->validateName($tableName);

        // ❌ NE PAS faire de validation ici (permet SUM, COUNT, alias, etc.)

        $sql = "SELECT $colSelect FROM $tableName";
        if ($condi) {
            $sql .= " $condi";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * set (UPDATE) sécurisé
     * $colunms = tableau des colonnes à mettre à jour
     * $vals = tableau des valeurs correspondantes
     * $condi = condition SQL avec placeholders (ex: "WHERE id = ?")
     * $condiParams = valeurs pour la condition
     */
    public function set($tableName, $colunms, $vals, $condi, $condiParams = [])
    {
        $tableName = $this->validateName($tableName);
        $this->validateColumns($colunms);

        $setPart = implode(' = ?, ', $colunms) . ' = ?';
        $sql = "UPDATE $tableName SET $setPart $condi";

        $stmt = $this->pdo->prepare($sql);
        $params = array_merge($vals, $condiParams);
        $resp = $stmt->execute($params);
        $stmt->closeCursor();

        return $resp;
    }

    /**
     * insert sécurisé
     * $colunms = tableau colonnes
     * $vals = valeurs à insérer
     */
    public function insert($tableName, $colunms, $vals)
    {
        $tableName = $this->validateName($tableName);
        $this->validateColumns($colunms);

        $cols = implode(',', $colunms);
        $placeholders = rtrim(str_repeat('?,', count($colunms)), ',');

        $sql = "INSERT INTO $tableName ($cols) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $resp = $stmt->execute($vals);
        $stmt->closeCursor();

        return $resp;
    }

    /**
     * personalDelete sécurisé
     * $condi doit contenir les placeholders
     * $params = valeurs pour la condition
     */
    public function personalDelete($tableName, $condi, $params = [])
    {
        $tableName = $this->validateName($tableName);

        $sql = "DELETE FROM $tableName $condi";
        $stmt = $this->pdo->prepare($sql);
        $resp = $stmt->execute($params);
        $stmt->closeCursor();

        return $resp;
    }

    /**
     * search sécurisé
     * $colSelect = colonnes à concaténer (ex: "col1, col2")
     * $params = tableau des valeurs pour LIKE avec %
     * $cond = condition additionnelle optionnelle (avec placeholders)
     */
    public function search($tableName, $colSelect, $params, $cond = '')
    {
        $tableName = $this->validateName($tableName);
        $cols = array_map('trim', explode(',', $colSelect));
        $this->validateColumns($cols);

        $sql = "SELECT * FROM $tableName WHERE CONCAT_WS(' ', $colSelect) LIKE ?";
        if ($cond) {
            $sql .= ' ' . $cond;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}
?>
