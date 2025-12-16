<?php
// public/docente_dashboard.php
session_start();
include_once '../includes/auth.php';
checkRole(['DOCENTE', 'ADMIN']); 

// Incluir configuración y modelos
include_once '../config/Database.php';
include_once '../classes/Horario.php'; 
include_once '../classes/Asistencia.php'; // Necesario para verificar si ya se tomó

// Asume que el ID del docente está en la sesión
$id_docente = $_SESSION['user_id']; 

// Inicialización de la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciar modelos
$horario = new Horario($db);
$asistencia = new Asistencia($db);

$clase_activa = null;
$asistencia_tomada = false;
$page_title = "Panel de Docente";
$fecha_actual = date('Y-m-d'); 

// 1. Obtener la clase activa para el docente en este momento
// Esta función requiere que Horario::getCurrentClassForDocente() esté funcionando
$clase_activa = $horario->getCurrentClassForDocente($id_docente);

if ($clase_activa) {
    // 2. Si hay clase activa, verificar si la asistencia ya fue tomada
    $id_horario = $clase_activa['ID_HORARIO'];
    
    // Si checkAssistanceTaken() devuelve true, ya se tomó.
    $asistencia_tomada = $asistencia->checkAssistanceTaken($id_horario, $fecha_actual);
}

// Cargar la vista
include_once '../views/docentes/dashboard_view.php';
?>