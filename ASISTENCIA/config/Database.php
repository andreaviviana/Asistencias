<?php

date_default_timezone_set('America/Bogota');
class Database {
    private $host = "localhost";
    private $db_name = "COLEGIO_ASISTENCIA";
    private $username = "root"; // ¡Cambia esto!
    private $password = ""; // ¡Cambia esto!
    public $conn;

    public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>