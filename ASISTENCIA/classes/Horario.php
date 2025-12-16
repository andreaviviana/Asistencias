<?php
// classes/Horario.php
include_once 'BaseModel.php';

class Horario extends BaseModel {
    public $table_name = "HORARIO";

    // ------------------------------------
    // C - CREATE (Crear Horario)
    // ------------------------------------
    public function create($id_materia, $id_curso, $dia_semana, $hora_inicio, $hora_fin) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET ID_MATERIA=:id_materia, ID_CURSO=:id_curso, 
                      DIA_SEMANA=:dia_semana, HORA_INICIO=:hora_inicio, HORA_FIN=:hora_fin";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento y Binding
        $dia_semana = htmlspecialchars(strip_tags($dia_semana));

        $stmt->bindParam(':id_materia', $id_materia);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':dia_semana', $dia_semana);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejo de errores (p. ej., si una FK no existe)
            return false;
        }
    }


    // ------------------------------------
    // R - READ ALL (Leer Todos con JOINs)
    // ------------------------------------
    public function readAll() {
        $query = "SELECT 
                      h.ID_HORARIO, h.DIA_SEMANA, h.HORA_INICIO, h.HORA_FIN,
                      m.NOMBRE_MATERIA,
                      d.NOMBRE AS NOMBRE_DOCENTE,
                      c.NOMBRE_CURSO,
                      g.NOMBRE_GRADO
                    FROM " . $this->table_name . " h
                    JOIN MATERIA m ON h.ID_MATERIA = m.ID_MATERIA
                    JOIN DOCENTE d ON m.ID_DOCENTE = d.ID_DOCENTE
                    JOIN CURSO c ON h.ID_CURSO = c.ID_CURSO
                    JOIN GRADO g ON c.ID_GRADO = g.ID_GRADO
                    -- Ordena por día y luego por hora de inicio
                    -- ⚠️ CORRECCIÓN: MIERCOLES sin tilde para el ORDER BY
                    ORDER BY FIELD(h.DIA_SEMANA, 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'), h.HORA_INICIO ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ------------------------------------
    // R - READ ONE (Leer Uno para edición)
    // ------------------------------------
    public function readOne($id) {
        $query = "SELECT 
                      h.ID_HORARIO, h.ID_MATERIA, h.ID_CURSO, h.DIA_SEMANA, h.HORA_INICIO, h.HORA_FIN,
                      m.NOMBRE_MATERIA,
                      d.NOMBRE AS NOMBRE_DOCENTE,
                      c.NOMBRE_CURSO,
                      g.NOMBRE_GRADO
                    FROM " . $this->table_name . " h
                    JOIN MATERIA m ON h.ID_MATERIA = m.ID_MATERIA
                    JOIN DOCENTE d ON m.ID_DOCENTE = d.ID_DOCENTE
                    JOIN CURSO c ON h.ID_CURSO = c.ID_CURSO
                    JOIN GRADO g ON c.ID_GRADO = g.ID_GRADO
                    WHERE h.ID_HORARIO = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ------------------------------------
    // U - UPDATE (Actualizar Horario)
    // ------------------------------------
    public function update($id, $id_materia, $id_curso, $dia_semana, $hora_inicio, $hora_fin) {
        $query = "UPDATE " . $this->table_name . " 
                  SET ID_MATERIA=:id_materia, ID_CURSO=:id_curso, 
                      DIA_SEMANA=:dia_semana, HORA_INICIO=:hora_inicio, HORA_FIN=:hora_fin
                  WHERE ID_HORARIO=:id";
        
        $stmt = $this->conn->prepare($query);

        // Saneamiento y Binding
        $dia_semana = htmlspecialchars(strip_tags($dia_semana));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_materia', $id_materia);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':dia_semana', $dia_semana);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // ------------------------------------
    // D - DELETE (Eliminar Horario)
    // ------------------------------------
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ID_HORARIO = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Error de Clave Foránea si hay asistencia vinculada
            return false;
        }
    }


    // ------------------------------------
    // R - READ CURRENT CLASS (Obtener Clase Activa para el Docente)
    // ------------------------------------
    public function getCurrentClassForDocente($id_docente) {
        
        // Mapeo de días de PHP (inglés) a tu ENUM (español sin tilde)
        $dias_map = [
            'MONDAY' => 'LUNES', 
            'TUESDAY' => 'MARTES', 
            'WEDNESDAY' => 'MIERCOLES', // ✅ Corregido para coincidir con el ENUM
            'THURSDAY' => 'JUEVES', 
            'FRIDAY' => 'VIERNES'
        ];
        
        // Obtiene el día actual en inglés (ej. 'Monday') y lo convierte a español
        $dia_semana_es = $dias_map[strtoupper(date('l'))] ?? null;

        if (!$dia_semana_es) {
            return false; // Es fin de semana
        }

        $hora_actual = date('H:i:s');
        
        // Consulta que filtra por Docente, Día y Hora Actual
        $query = "SELECT 
                        h.ID_HORARIO, h.ID_CURSO, m.ID_MATERIA, h.HORA_INICIO, h.HORA_FIN,
                        c.NOMBRE_CURSO, m.NOMBRE_MATERIA
                    FROM " . $this->table_name . " h
                    JOIN MATERIA m ON h.ID_MATERIA = m.ID_MATERIA
                    JOIN CURSO c ON h.ID_CURSO = c.ID_CURSO
                    WHERE m.ID_DOCENTE = :id_docente
                    AND h.DIA_SEMANA = :dia_semana
                    AND h.HORA_INICIO <= :hora_actual
                    AND h.HORA_FIN >= :hora_actual
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        
        // Binding de parámetros
        $stmt->bindParam(':id_docente', $id_docente);
        $stmt->bindParam(':dia_semana', $dia_semana_es);
        $stmt->bindParam(':hora_actual', $hora_actual);
        
        $stmt->execute(); 
        
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
}