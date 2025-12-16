<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

include_once '../classes/Estudiante.php';
// Nota: Necesitarás clases para CURSO y GRADO si quieres un desplegable en CREATE/UPDATE

$estudiante = new Estudiante();
$stmt = $estudiante->readAll();

$message = '';
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'creado') {
        $message = "✅ ¡Estudiante registrado con éxito!";
    } elseif ($_GET['action'] == 'actualizado') {
        $message = "📝 ¡Estudiante actualizado con éxito!";
    } elseif ($_GET['action'] == 'eliminado') {
        $message = "🗑️ Estudiante eliminado correctamente.";
    }
}

$page_title = "Listado de Estudiantes";
include_once '../views/estudiantes/list_view.php'; 
?>