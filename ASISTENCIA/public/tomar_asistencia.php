<?php
session_start();
include_once '../includes/auth.php';
// Solo el rol DOCENTE (o ADMIN) puede tomar asistencia
checkRole(['DOCENTE', 'ADMIN']); 

// Incluir configuración y modelos
include_once '../config/Database.php'; 
include_once '../classes/Asistencia.php';
include_once '../classes/Estudiante.php';
include_once '../classes/Horario.php';

$database = new Database();
$db = $database->getConnection();

$asistencia = new Asistencia($db);
$estudiante = new Estudiante($db);
$horario = new Horario($db);

$message = '';

// =======================================================
// ✅ CORRECCIÓN 1: INICIALIZACIÓN COMPLETA DE TODAS LAS VARIABLES DE LA VISTA
// Esto resuelve todos los "Undefined variable" si el script llega aquí.
// =======================================================
$fecha_registro = date('Y-m-d'); // <-- Ya definida, resuelve otro Warning.
$estudiantes_stmt = null;        // <-- Ya definida.
$clase_info = null;              // <-- Ya definida.
$page_title = "Cargando Módulo de Asistencia..."; // Valor por defecto

$id_horario = 0; 
$id_curso = 0;   


// 1. Obtener IDs (Horario y Curso)
if (isset($_GET['id_horario']) && isset($_GET['id_curso'])) {
    $id_horario = (int)$_GET['id_horario'];
    $id_curso = (int)$_GET['id_curso'];
} elseif (isset($_POST['id_horario']) && isset($_POST['id_curso'])) {
    // Si viene de POST, usamos los IDs ocultos
    $id_horario = (int)$_POST['id_horario'];
    $id_curso = (int)$_POST['id_curso'];
} else {
    // Si faltan IDs, forzamos una salida con mensaje claro
    die("ERROR FATAL: Acceso denegado. Faltan los parámetros 'id_horario' y 'id_curso'. Vuelva al Panel de Docente.");
}


// 2. Procesar el formulario POST (Guardar Asistencia)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asistencia'])) {
    
    $asistencia_data = $_POST['asistencia'];
    $success_count = 0;
    $error_flag = false;
    
    // Antes de registrar, verificar si ya se tomó para evitar duplicados
    if ($asistencia->checkAssistanceTaken($id_horario, $fecha_registro)) {
        $message = "❌ Error: La asistencia para esta clase ya fue registrada hoy.";
        $error_flag = true;
    } else {
        // ... (Tu lógica de guardado de asistencia con transacción) ...
        $db->beginTransaction();

        foreach ($asistencia_data as $id_estudiante => $estado) {
             if ($asistencia->create($id_horario, $id_estudiante, $fecha_registro, $estado)) {
                 $success_count++;
             } else {
                 $error_flag = true;
             }
         }

        if ($error_flag) {
            $db->rollBack();
            $message = "❌ Error al guardar la asistencia de algunos estudiantes. No se registró nada para evitar inconsistencias.";
        } else {
            $db->commit();
            // Redirigir al dashboard con un mensaje de éxito y TERMINAR
            header("Location: docente_dashboard.php?action=asistencia_registrada");
            exit();
        }
    }
}

// 3. Cargar los datos para la vista (Lógica principal)

// Obtener detalles del horario (para mostrar en la vista)
$clase_info = $horario->readOne($id_horario);

if (!$clase_info) {
    // Si la clase no existe, forzamos una salida con mensaje claro
    die("ERROR FATAL: El ID de Horario proporcionado no es válido.");
}

// Obtener la lista de estudiantes de este curso (SOLO SI EL ID_CURSO ES VALIDO)
$estudiantes_stmt = $estudiante->readStudentsByCourse($id_curso);

// El page_title depende de $clase_info, por eso va después de la verificación.
$page_title = "Tomar Asistencia: " . htmlspecialchars($clase_info['NOMBRE_MATERIA']) . " (". htmlspecialchars($clase_info['NOMBRE_CURSO']) . ")";

// Incluir la vista
include_once '../views/asistencia/tomar_asistencia_view.php';
?>