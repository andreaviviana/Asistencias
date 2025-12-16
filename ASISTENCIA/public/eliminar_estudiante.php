<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

include_once '../config/Database.php'; // Ya incluido aquí
include_once '../classes/Estudiante.php';

$database = new Database();
$db = $database->getConnection();

// Instanciar modelo con la conexión ($db)
$estudiante = new Estudiante($db);

// Verificar que se haya pasado un ID
if (isset($_GET['id'])) {
    $id_estudiante = (int)$_GET['id'];
    
    // Llamar al método delete
    if ($estudiante->delete($id_estudiante)) {
        // Éxito - Redirigir usando 'eliminado'
        header("Location: estudiantes.php?action=eliminado");
        exit();
    } else {
        // Error (ej. tiene registros de asistencia asociados)
        header("Location: estudiantes.php?msg=Error_al_eliminar_o_datos_asociados");
        exit();
    }
} else {
    // Si no se proporcionó ID
    header("Location: estudiantes.php?msg=ID_faltante");
    exit();
}
?>