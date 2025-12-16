<?php
session_start();
include_once '../includes/auth.php';
// Solo el rol ADMIN puede acceder al listado de horarios
checkRole(['ADMIN']); 

// Incluir la configuración y la clase Horario
include_once '../config/Database.php'; // 1. ¡INCLUIDO!
include_once '../classes/Horario.php'; 

// Inicialización de la base de datos
$database = new Database();
$db = $database->getConnection();

// 2. Instanciar el modelo Horario con la conexión
$horario = new Horario($db); 

// Llamar al método readAll para obtener el listado de horarios
$stmt = $horario->readAll(); 

$message = '';

// 3. Manejar mensajes de confirmación (CRUD)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'creado') {
        $message = "✅ Horario creado con éxito."; // YA TIENE EL ICONO
    } elseif ($action == 'actualizado') {
        $message = "✅ Horario actualizado con éxito."; // YA TIENE EL ICONO
    } elseif ($action == 'eliminado') {
        $message = "✅ Horario eliminado con éxito."; // YA TIENE EL ICONO
    }
}

// Manejar mensajes de error
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg == 'Error_al_eliminar') {
        $message = "❌ Error: El horario no pudo ser eliminado. Puede tener registros de asistencia asociados."; // YA TIENE EL ICONO
    }
    // ...
}

$page_title = "Gestión de Horarios";

// Cargar la vista de listado
include_once '../views/horarios/list_view.php';
?>