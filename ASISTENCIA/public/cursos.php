<?php
session_start();
include_once '../includes/auth.php';
// Solo los administradores pueden gestionar esto
checkRole(['ADMIN']); 

// Incluir la clase del Modelo
include_once '../config/Database.php';
include_once '../classes/Curso.php'; 

// Inicialización
$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);

// Llamar a la función readAll() para obtener la lista de cursos con el nombre del grado
// NOTA: Asumo que tu clase Curso tiene un método readAll() que hace JOIN con GRADO.
$stmt_cursos = $curso->readAll(); 

$message = '';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'creado') {
        $message = "✅ Curso creado con éxito.";
    } elseif ($action == 'actualizado') {
        $message = "✅ Curso actualizado con éxito.";
    } elseif ($action == 'eliminado') {
        $message = "✅ Curso eliminado con éxito.";
    }
}

// Manejar mensajes de error (Errores de dependencia/eliminación)
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 'Error_al_eliminar_dependencia') {
        $message = "❌ Error: El curso no puede ser eliminado porque tiene estudiantes o horarios asociados.";
    }
    if ($msg == 'ID_faltante') {
        $message = "❌ Error: ID de curso o grado faltante.";
    }
    if ($msg == 'Curso_no_encontrado') {
        $message = "❌ Error: El curso solicitado no fue encontrado.";
    }
}

$page_title = "Gestionar Cursos y Grados";

// Incluir la vista
include_once '../views/cursos/list_view.php';
?>