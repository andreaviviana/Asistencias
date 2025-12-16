<?php
// Asegúrate que esta ruta es correcta según donde esté BaseModel.php
include_once 'BaseModel.php'; 

class Estudiante extends BaseModel {
    public $table_name = "ESTUDIANTE";
    
    // ------------------------------------
    // C - CREATE (Crear)
    // ------------------------------------
    public function create($nombre, $documento, $fecha_nacimiento, $id_curso) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET NOMBRE=:nombre, DOCUMENTO=:documento, FECHA_NACIMIENTO=:fecha_nacimiento, ID_CURSO=:id_curso";
        
        $stmt = $this->conn->prepare($query);
        
        // Saneamiento de datos (seguridad básica)
        $nombre = htmlspecialchars(strip_tags($nombre));
        $documento = htmlspecialchars(strip_tags($documento));
        // No saneamos fecha_nacimiento y id_curso tan agresivamente, asumimos validación previa.

        // Binding de parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':id_curso', $id_curso);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ------------------------------------
    // R - READ ONE (Leer Uno, para edición)
    // ------------------------------------
    public function readOne($id) {
        $query = "SELECT e.*, c.NOMBRE_CURSO, g.NOMBRE_GRADO
                  FROM " . $this->table_name . " e 
                  JOIN CURSO c ON e.ID_CURSO = c.ID_CURSO
                  JOIN GRADO g ON c.ID_GRADO = g.ID_GRADO
                  WHERE e.ID_ESTUDIANTE = :id
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un array asociativo con los datos
    }

    // ------------------------------------
    // R - READ ALL (Leer Todos, para listado)
    // ------------------------------------
    public function readAll() {
        $query = "SELECT e.ID_ESTUDIANTE, e.NOMBRE, e.DOCUMENTO, c.NOMBRE_CURSO, g.NOMBRE_GRADO
                  FROM " . $this->table_name . " e 
                  JOIN CURSO c ON e.ID_CURSO = c.ID_CURSO
                  JOIN GRADO g ON c.ID_GRADO = g.ID_GRADO
                  ORDER BY g.NOMBRE_GRADO, c.NOMBRE_CURSO, e.NOMBRE ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ------------------------------------
    // R - READ STUDENTS BY COURSE (Leer estudiantes por ID de Curso)
    // ESTE ES EL MÉTODO CLAVE PARA LA TOMA DE ASISTENCIA
    // ------------------------------------
    public function readStudentsByCourse($id_curso) {
        $query = "SELECT ID_ESTUDIANTE, NOMBRE, DOCUMENTO 
                  FROM " . $this->table_name . " 
                  WHERE ID_CURSO = :id_curso 
                  ORDER BY NOMBRE ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->execute();
        return $stmt;
    }

    // ------------------------------------
    // U - UPDATE (Actualizar)
    // ------------------------------------
    public function update($id, $nombre, $documento, $fecha_nacimiento, $id_curso) {
        $query = "UPDATE " . $this->table_name . " 
                  SET NOMBRE=:nombre, DOCUMENTO=:documento, FECHA_NACIMIENTO=:fecha_nacimiento, ID_CURSO=:id_curso 
                  WHERE ID_ESTUDIANTE=:id";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento de datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $documento = htmlspecialchars(strip_tags($documento));
        
        // Binding de parámetros
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':id_curso', $id_curso);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ------------------------------------
    // D - DELETE (Eliminar)
    // ------------------------------------
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID_ESTUDIANTE = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>