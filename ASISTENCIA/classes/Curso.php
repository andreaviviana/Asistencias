<?php
// classes/Curso.php
include_once 'BaseModel.php';

class Curso extends BaseModel {
    public $table_name = "CURSO";
    
    // ------------------------------------
    // C - CREATE (Crear Curso) - ¡AÑADIDO!
    // ------------------------------------
    public function create($nombre_curso, $id_grado) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET NOMBRE_CURSO=:nombre, ID_GRADO=:id_grado";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento de datos
        $nombre_curso = htmlspecialchars(strip_tags($nombre_curso));
        
        // Binding de parámetros
        $stmt->bindParam(':nombre', $nombre_curso);
        $stmt->bindParam(':id_grado', $id_grado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // ------------------------------------
    // R - READ ALL (Leer Todos con JOIN a GRADO)
    // ------------------------------------
    public function readAll() {
        $query = "SELECT c.ID_CURSO, c.NOMBRE_CURSO, g.NOMBRE_GRADO 
                  FROM " . $this->table_name . " c
                  JOIN GRADO g ON c.ID_GRADO = g.ID_GRADO 
                  ORDER BY g.NOMBRE_GRADO, c.NOMBRE_CURSO ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ------------------------------------
    // R - READ ONE (Necesario para el UPDATE)
    // ------------------------------------
    public function readOne($id) {
        $query = "SELECT c.ID_CURSO, c.NOMBRE_CURSO, c.ID_GRADO, g.NOMBRE_GRADO 
                  FROM " . $this->table_name . " c
                  JOIN GRADO g ON c.ID_GRADO = g.ID_GRADO
                  WHERE c.ID_CURSO = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ------------------------------------
    // U - UPDATE (Actualizar Curso) - ¡AÑADIDO!
    // ------------------------------------
    public function update($id, $nombre_curso, $id_grado) {
        $query = "UPDATE " . $this->table_name . " 
                  SET NOMBRE_CURSO=:nombre, ID_GRADO=:id_grado 
                  WHERE ID_CURSO=:id";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento de datos
        $nombre_curso = htmlspecialchars(strip_tags($nombre_curso));
        
        // Binding de parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre_curso);
        $stmt->bindParam(':id_grado', $id_grado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ------------------------------------
    // D - DELETE (Eliminar Curso) - ¡AÑADIDO!
    // ------------------------------------
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID_CURSO = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Error si hay estudiantes asociados (Foreign Key Constraint)
            return false;
        }
    }
}
?>