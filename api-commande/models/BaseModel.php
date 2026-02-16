<?php
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ .'/../config/bdFonctions.class.php';

class BaseModel extends bdFonctions {
    public function __construct() {
    	$this->db = new Database();
        parent::__construct();
    }
}
