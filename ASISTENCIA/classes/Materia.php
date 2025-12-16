<?php
// classes/Materia.php
include_once 'BaseModel.php';

class Materia extends BaseModel {
    public $table_name = "MATERIA";
    
    // ------------------------------------
    // C - CREATE (Crear Materia)
    // ------------------------------------
    public function create($nombre_materia, $id_docente) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET NOMBRE_MATERIA=:nombre, ID_DOCENTE=:id_docente";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento y binding
        $nombre_materia = htmlspecialchars(strip_tags($nombre_materia));
        
        $stmt->bindParam(':nombre', $nombre_materia);
        $stmt->bindParam(':id_docente', $id_docente);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // ------------------------------------
    // R - READ ALL (Leer Todos con JOIN al Docente asignado)
    // ------------------------------------
    public function readAll() {
        $query = "SELECT m.ID_MATERIA, m.NOMBRE_MATERIA, 
                         d.ID_DOCENTE, d.NOMBRE AS NOMBRE_DOCENTE 
                  FROM " . $this->table_name . " m
                  JOIN DOCENTE d ON m.ID_DOCENTE = d.ID_DOCENTE
                  ORDER BY m.NOMBRE_MATERIA ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ------------------------------------
    // R - READ ONE (Necesario para la edición)
    // ------------------------------------
    public function readOne($id) {
        $query = "SELECT m.ID_MATERIA, m.NOMBRE_MATERIA, m.ID_DOCENTE, d.NOMBRE AS NOMBRE_DOCENTE
                  FROM " . $this->table_name . " m
                  JOIN DOCENTE d ON m.ID_DOCENTE = d.ID_DOCENTE
                  WHERE m.ID_MATERIA = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ------------------------------------
    // U - UPDATE (Actualizar Materia)
    // ------------------------------------
    public function update($id, $nombre_materia, $id_docente) {
        $query = "UPDATE " . $this->table_name . " 
                  SET NOMBRE_MATERIA=:nombre, ID_DOCENTE=:id_docente
                  WHERE ID_MATERIA=:id";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento de datos
        $nombre_materia = htmlspecialchars(strip_tags($nombre_materia));
        
        // Binding de parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre_materia);
        $stmt->bindParam(':id_docente', $id_docente);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ------------------------------------
    // D - DELETE (Eliminar Materia)
    // ------------------------------------
    public function delete($id) {
        // NOTA: Si esta materia tiene registros en HORARIO, la base de datos no permitirá la eliminación
        $query = "DELETE FROM " . $this->table_name . " WHERE ID_MATERIA = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Error si hay horarios asociados (Foreign Key Constraint)
            return false;
        }
    }
}
?>