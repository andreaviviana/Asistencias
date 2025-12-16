<?php
// classes/Grado.php
include_once 'BaseModel.php';

class Grado extends BaseModel {
    public $table_name = "GRADO";

    // ------------------------------------
    // C - CREATE (Crear Nuevo Grado)
    // ------------------------------------
    public function create($nombre_grado) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET NOMBRE_GRADO=:nombre_grado";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento
        $nombre_grado = htmlspecialchars(strip_tags($nombre_grado));
        
        $stmt->bindParam(':nombre_grado', $nombre_grado);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejo de error si ya existe un grado con el mismo nombre (si tienes restricción UNIQUE)
            return false;
        }
    }

    // ------------------------------------
    // R - READ ALL (Listar todos los grados)
    // ------------------------------------
    public function readAll() {
        $query = "SELECT ID_GRADO, NOMBRE_GRADO FROM " . $this->table_name . " ORDER BY NOMBRE_GRADO ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Opcional: Implementar readOne, update, delete
}
?>