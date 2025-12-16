<?php
// Asegúrate que esta ruta es correcta según donde esté BaseModel.php
include_once 'BaseModel.php';

class Asistencia extends BaseModel {
    public $table_name = "ASISTENCIA";

    // Mapeo de valores del formulario a los valores de la Base de Datos (ENUM)
    private $estado_map = [
        'Presente' => 'ASISTIÓ',
        'Ausente' => 'FALTÓ',
        'Tardanza' => 'TARDANZA',
        'Justificado' => 'JUSTIFICADO' // Este puede ser útil para correcciones posteriores
    ];

    // ------------------------------------
    // C - CREATE/UPDATE (Registrar Asistencia)
    // ------------------------------------
    public function create($id_horario, $id_estudiante, $fecha_registro, $estado, $observaciones = null) {
        
        // 🚨 CORRECCIÓN 2B: Mapear el estado del formulario al ENUM de la BD
        $estado_bd = $this->estado_map[$estado] ?? 'FALTÓ'; // Usa FALTÓ como fallback seguro
        
        // 1. Verificar si ya existe un registro de asistencia para este Estudiante/Horario/Día
        $check_query = "SELECT ID_ASISTENCIA FROM " . $this->table_name . " 
                        WHERE ID_ESTUDIANTE = :id_estudiante 
                        AND ID_HORARIO = :id_horario 
                        AND FECHA = :fecha
                        LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        
        $check_stmt->bindParam(':id_estudiante', $id_estudiante);
        $check_stmt->bindParam(':id_horario', $id_horario);
        $check_stmt->bindParam(':fecha', $fecha_registro); 
        
        $check_stmt->execute();
        
        // 2. Determinar si se INSERTARÁ o se ACTUALIZARÁ
        if ($check_stmt->rowCount() > 0) {
            // Ya existe, usamos UPDATE
            // 🚨 CORRECCIÓN 2A: HORA_REGISTRO ELIMINADO
            $query = "UPDATE " . $this->table_name . " 
                      SET ESTADO=:estado, OBSERVACIONES=:observaciones
                      WHERE ID_ESTUDIANTE = :id_estudiante AND ID_HORARIO = :id_horario AND FECHA = :fecha";
        } else {
            // No existe, usamos INSERT
            // 🚨 CORRECCIÓN 2A: HORA_REGISTRO ELIMINADO
            $query = "INSERT INTO " . $this->table_name . " 
                      SET ID_ESTUDIANTE=:id_estudiante, ID_HORARIO=:id_horario, 
                          FECHA=:fecha, ESTADO=:estado, OBSERVACIONES=:observaciones";
        }

        $stmt = $this->conn->prepare($query);
        
        // Saneamiento y Binding
        // Usamos $estado_bd (el valor mapeado)
        $estado_bd = htmlspecialchars(strip_tags($estado_bd)); 
        $observaciones = $observaciones ? htmlspecialchars(strip_tags($observaciones)) : null;
        
        $stmt->bindParam(':id_estudiante', $id_estudiante);
        $stmt->bindParam(':id_horario', $id_horario);
        $stmt->bindParam(':fecha', $fecha_registro);
        $stmt->bindParam(':estado', $estado_bd); // <-- BINDING CON EL VALOR CORREGIDO
        $stmt->bindParam(':observaciones', $observaciones);

        try {
            if($stmt->execute()){
                return true;
            }
        } catch (PDOException $e) {
            // Si el error persiste, detén la ejecución para ver el mensaje.
            echo "<h3>🚨 ERROR FATAL SQL EN CLASE ASISTENCIA</h3>";
            echo "<p><strong>Consulta:</strong> " . $query . "</p>";
            echo "<p><strong>Mensaje de BD:</strong> " . $e->getMessage() . "</p>";
            die(); 
        }
        return false;
    }
    
    // ------------------------------------
    // R - READ ASISTENCIA BY DATE AND HORARIO (Para reportes/vistas)
    // ------------------------------------
    public function readByHorarioAndDate($id_horario, $fecha) {
        $query = "SELECT a.ESTADO, a.OBSERVACIONES, e.NOMBRE, e.DOCUMENTO
                  FROM " . $this->table_name . " a
                  JOIN ESTUDIANTE e ON a.ID_ESTUDIANTE = e.ID_ESTUDIANTE
                  WHERE a.ID_HORARIO = :id_horario
                  AND a.FECHA = :fecha
                  ORDER BY e.NOMBRE ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_horario', $id_horario);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt;
    }
    
    // ------------------------------------
    // R - READ (Verificar si la asistencia ya fue tomada para un horario/fecha)
    // ------------------------------------
    public function checkAssistanceTaken($id_horario, $fecha) {
        $query = "SELECT ID_ASISTENCIA 
                  FROM " . $this->table_name . " 
                  WHERE ID_HORARIO = :id_horario 
                  AND FECHA = :fecha
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_horario', $id_horario);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>