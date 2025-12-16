<?php
session_start();
include_once '../includes/auth.php';
// Solo el rol ADMIN puede acceder a esta página
checkRole(['ADMIN']); 

// Incluir configuración y modelo
include_once '../config/Database.php';
include_once '../classes/Horario.php'; // Clase Horario

$database = new Database();
$db = $database->getConnection();

// Instanciar modelo
$horario = new Horario($db);

// Verificar que se haya pasado un ID
if (isset($_GET['id'])) {
    $id_horario = (int)$_GET['id'];
    
    // Llamar al método delete
    if ($horario->delete($id_horario)) {
        // Éxito - Redirigir usando 'eliminado'
        header("Location: horarios.php?action=eliminado");
        exit();
    } else {
        // Error, generalmente debido a que el horario está siendo usado en la tabla ASISTENCIA.
        header("Location: horarios.php?msg=Error_al_eliminar");
        exit();
    }
} else {
    // Si no se proporcionó ID
    header("Location: horarios.php?msg=ID_faltante");
    exit();
}
?>