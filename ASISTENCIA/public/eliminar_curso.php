<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

include_once '../config/Database.php';
include_once '../classes/Curso.php';

$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);

if (isset($_GET['id'])) {
    $id_curso = (int)$_GET['id'];
    
    if ($curso->delete($id_curso)) {
        header("Location: cursos.php?action=eliminado");
        exit();
    } else {
        // Error de clave foránea si hay estudiantes o horarios vinculados
        header("Location: cursos.php?msg=Error_al_eliminar_dependencia");
        exit();
    }
} else {
    header("Location: cursos.php?msg=ID_faltante");
    exit();
}
?>