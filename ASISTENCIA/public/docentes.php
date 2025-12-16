<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

include_once '../classes/Docente.php';

$docente = new Docente();
$stmt = $docente->readAll();

// Mensaje de éxito o error después de una acción
$message = '';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'creado') {
        $message = "✅ Docente creado con éxito.";
    } elseif ($action == 'actualizado') {
        $message = "✅ Docente actualizado con éxito.";
    } elseif ($action == 'eliminado') {
        $message = "✅ Docente eliminado con éxito.";
    }
}

// Manejar mensajes de error (Errores de dependencia/eliminación)
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 'Error_al_eliminar_dependencia') {
        $message = "❌ Error: El Docente no puede ser eliminado porque tiene estudiantes o horarios asociados.";
    }
    if ($msg == 'ID_faltante') {
        $message = "❌ Error: ID de Docente o grado faltante.";
    }
    if ($msg == 'Curso_no_encontrado') {
        $message = "❌ Error: El Docente solicitado no fue encontrado.";
    }
}

$page_title = "Listado de Docentes";
include_once '../views/docentes/list_view.php'; 
?>