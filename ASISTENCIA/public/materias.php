<?php
session_start();
include_once '../includes/auth.php';
// Solo el rol ADMIN puede acceder al listado de materias
checkRole(['ADMIN']); 

// Incluir las clases necesarias
include_once '../config/Database.php';
include_once '../classes/Materia.php'; // Clase Materia que acabamos de crear

$database = new Database();
$db = $database->getConnection();

// Instanciar el modelo Materia
$materia = new Materia($db);

// Llamar al método readAll para obtener el listado de materias con su docente
$stmt = $materia->readAll(); 

$message = '';

// 1. Manejar mensajes de confirmación (CRUD)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'creado') {
        $message = "✅ Materia creada con éxito.";
    } elseif ($action == 'actualizado') {
        $message = "✅ Materia actualizada con éxito.";
    } elseif ($action == 'eliminado') {
        $message = "✅ Materia eliminada con éxito.";
    }
}

// 2. Manejar mensajes de error (si vienen de otros controladores)
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 'Error_al_eliminar') {
        $message = "❌ Error: La materia no pudo ser eliminada. Puede tener horarios o registros asociados.";
    }
}

$page_title = "Listado de Materias";

// Cargar la vista de listado
include_once '../views/materias/list_view.php';
?>