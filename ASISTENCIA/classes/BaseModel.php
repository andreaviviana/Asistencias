<?php
// Incluir la conexión a la base de datos
include_once '../config/Database.php';

class BaseModel {
    protected $conn;
    protected $table_name;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
}
?>