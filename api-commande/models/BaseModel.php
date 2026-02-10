<?php
require_once './../config/database.php';
require_once './../config/bdFonctions.class.php';

class BaseModel extends bdFonctions {
    public function __construct() {
    	$this->db = new Database();
        parent::__construct();
    }
}
