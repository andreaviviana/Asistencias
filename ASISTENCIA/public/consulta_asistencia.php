<?php
session_start();
include_once '../includes/auth.php';
// Permitir acceso a ADMIN y DOCENTE (para que el docente vea sus propios reportes)
checkRole(['ADMIN', 'DOCENTE']); 

include_once '../classes/Horario.php';
include_once '../classes/Asistencia.php';

$horario_obj = new Horario();
$asistencia_obj = new Asistencia();

$horarios_stmt = $horario_obj->readAll(); // Obtener todos los horarios para el selector
$asistencia_registrada = null;
$horario_seleccionado = null;
$fecha_seleccionada = date('Y-m-d'); // Fecha por defecto: hoy

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_horario = $_POST['id_horario'];
    $fecha = $_POST['fecha'];

    if (empty($id_horario) || empty($fecha)) {
        $message = "Debe seleccionar un Horario y una Fecha.";
    } else {
        // Ejecutar la consulta de asistencia
        $asistencia_registrada = $asistencia_obj->readByHorarioAndDate($id_horario, $fecha);
        $horario_seleccionado = $horario_obj->readOne($id_horario); // Necesitas el readOne en Horario.php

        $fecha_seleccionada = $fecha;
    }
}

$page_title = "Consulta de Asistencia";
include_once '../views/asistencia/consulta_view.php'; 
?>