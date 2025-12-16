<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// Incluir configuración y modelo
include_once '../config/Database.php';
include_once '../classes/Materia.php'; // Clase Materia

$database = new Database();
$db = $database->getConnection();

// Instanciar modelo
$materia = new Materia($db);

// Verificar que se haya pasado un ID
if (isset($_GET['id'])) {
    $id_materia = (int)$_GET['id'];
    
    // Llamar al método delete
    if ($materia->delete($id_materia)) {
        // Éxito - Redirigir usando 'eliminado'
        header("Location: materias.php?action=eliminado");
        exit();
    } else {
        // Error, generalmente debido a que la materia está siendo usada en la tabla HORARIO.
        header("Location: materias.php?msg=Error_al_eliminar");
        exit();
    }
} else {
    // Si no se proporcionó ID
    header("Location: materias.php?msg=ID_faltante");
    exit();
}
?>