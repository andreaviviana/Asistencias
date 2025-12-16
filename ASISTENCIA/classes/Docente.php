<?php
include_once 'BaseModel.php';

class Docente extends BaseModel {
    public $table_name = "DOCENTE";

    // ------------------------------------
    // C - CREATE (Crear)
    // ------------------------------------
    public function create($nombre, $email, $telefono, $documento) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET NOMBRE=:nombre, EMAIL=:email, TELEFONO=:telefono, DOCUMENTO=:documento";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento de datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $email = htmlspecialchars(strip_tags($email));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $documento = htmlspecialchars(strip_tags($documento));

        // Binding de parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':documento', $documento);

        if($stmt->execute()) {
            return true;
        }
        // Retorna false si hay un error (ej. email o documento duplicado)
        return false;
    }

    // ------------------------------------
    // R - READ ALL (Leer Todos)
    // ------------------------------------
    public function readAll() {
        $query = "SELECT ID_DOCENTE, NOMBRE, EMAIL, TELEFONO, DOCUMENTO FROM " . $this->table_name . " ORDER BY NOMBRE ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // ------------------------------------
    // R - READ ONE (Leer Uno, para edición)
    // ------------------------------------
    public function readOne($id) {
        $query = "SELECT ID_DOCENTE, NOMBRE, EMAIL, TELEFONO, DOCUMENTO FROM " . $this->table_name . " WHERE ID_DOCENTE = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ------------------------------------
    // U - UPDATE (Actualizar) - ¡AÑADIDO!
    // ------------------------------------
    public function update($id, $nombre, $email, $telefono, $documento) {
        $query = "UPDATE " . $this->table_name . " 
                  SET NOMBRE=:nombre, EMAIL=:email, TELEFONO=:telefono, DOCUMENTO=:documento 
                  WHERE ID_DOCENTE=:id";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento de datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $email = htmlspecialchars(strip_tags($email));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $documento = htmlspecialchars(strip_tags($documento));
        
        // Binding de parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':documento', $documento);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
             // Manejo de error si hay conflicto de unicidad (documento/email)
            return false;
        }
    }

    // ------------------------------------
    // D - DELETE (Eliminar) - ¡AÑADIDO!
    // ------------------------------------
    public function delete($id) {
        // NOTA: Si este docente tiene materias o horarios asociados, la DB podría
        // lanzar un error de Foreign Key. Depende de la configuración de tu BD.
        $query = "DELETE FROM " . $this->table_name . " WHERE ID_DOCENTE = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Error si hay datos asociados (Foreign Key Constraint)
            return false;
        }
    }
}
?>