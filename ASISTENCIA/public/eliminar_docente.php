<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

include_once '../config/Database.php';
include_once '../classes/Docente.php'; // ¡Cambiado a Docente!

$database = new Database();
$db = $database->getConnection();

// Instanciar modelo con la conexión ($db)
$docente = new Docente($db); // ¡Cambiado a Docente!

// Verificar que se haya pasado un ID
if (isset($_GET['id'])) {
    $id_docente = (int)$_GET['id']; // Cambiado a id_docente
    
    // Llamar al método delete (que ya implementamos en Docente.php)
    if ($docente->delete($id_docente)) {
        // Éxito - Redirigir a DOCENTES con el action=eliminado
        header("Location: docentes.php?action=eliminado");
        exit();
    } else {
        // Error (ej. tiene materias o horarios asociados)
        header("Location: docentes.php?msg=Error_al_eliminar_o_materias_asociadas");
        exit();
    }
} else {
    // Si no se proporcionó ID
    header("Location: docentes.php?msg=ID_faltante");
    exit();
}
?>