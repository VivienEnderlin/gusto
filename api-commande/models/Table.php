<?php
require_once 'BaseModel.php';

class Table extends BaseModel {

    public function getAll() {
        return $this->getAll("tables")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->getAll("tables", "WHERE idtables = ?", [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($idtable) {
        return $this->insert("tables", ["idtable"], [$idtable]);
    }

    public function update($id, $idtable) {
        return $this->set("tables", ["idtable"], [$idtable], "WHERE idtables = ?", [$id]);
    }

    public function delete($id) {
        return $this->personalDelete("tables", "WHERE idtables = ?", [$id]);
    }
}
