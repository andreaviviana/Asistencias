<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

include_once '../config/Database.php';
include_once '../classes/Curso.php';
include_once '../classes/Grado.php';

$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);
$grado = new Grado($db); // Necesario para listar los grados en el selector

$message = '';
$curso_data = null;
$id_curso = null;

// Obtener todos los grados para el selector
$grados_stmt = $grado->readAll();

// 1. Determinar el ID del curso a editar
if (isset($_GET['id'])) {
    $id_curso = $_GET['id'];
} elseif (isset($_POST['id_curso'])) {
    $id_curso = $_POST['id_curso'];
} else {
    header("Location: cursos.php?msg=ID_faltante");
    exit();
}

// 2. Procesar el formulario POST (Actualización)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_curso = htmlspecialchars(strip_tags($_POST['nombre_curso']));
    $id_grado = (int)$_POST['id_grado'];

    if ($curso->update($id_curso, $nombre_curso, $id_grado)) {
        header("Location: cursos.php?action=actualizado");
        exit();
    } else {
        $message = "❌ Error al actualizar el curso. Asegúrese de que el nombre no esté duplicado.";
    }
}

// 3. Cargar los datos del curso para precargar el formulario
$curso_data = $curso->readOne($id_curso);

if (!$curso_data) {
    header("Location: cursos.php?msg=Curso_no_encontrado");
    exit();
}

$page_title = "Editar Curso: " . htmlspecialchars($curso_data['NOMBRE_CURSO']);
include_once '../views/cursos/edit_view.php';
?>