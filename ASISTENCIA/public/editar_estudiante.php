<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// Incluir las clases necesarias
include_once '../config/Database.php';
include_once '../classes/Estudiante.php';
include_once '../classes/Curso.php';


$database = new Database();
$db = $database->getConnection();

// Instanciar modelos con la conexión ($db)
$estudiante = new Estudiante($db);
$curso = new Curso($db);

$message = '';
$estudiante_data = [];
$id_estudiante = null;

// 1. Determinar el ID del estudiante a editar
// ... (lógica de obtención de ID sin cambios, es correcta)
if (isset($_GET['id'])) {
    $id_estudiante = $_GET['id'];
} elseif (isset($_POST['id_estudiante'])) {
    $id_estudiante = $_POST['id_estudiante'];
} else {
    header("Location: estudiantes.php?msg=ID_faltante");
    exit();
}

// 2. Procesar el formulario POST (Actualización)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $documento = htmlspecialchars(strip_tags($_POST['documento']));
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_curso = (int)$_POST['id_curso'];

    if ($estudiante->update($id_estudiante, $nombre, $documento, $fecha_nacimiento, $id_curso)) {
        // Redirigir al listado después de actualizar
        header("Location: estudiantes.php?action=actualizado");
        exit();
    } else {
        $message = "Error al actualizar el estudiante. Puede que el documento ya exista o la conexión falló.";
    }
}

// 3. Cargar los datos del estudiante para precargar el formulario
// Hacemos esto DESPUÉS del POST para recargar los datos actualizados si hubo un error en el update
$estudiante_data = $estudiante->readOne($id_estudiante);

if (!$estudiante_data) {
    header("Location: estudiantes.php?msg=Estudiante_no_encontrado");
    exit();
}

// 4. Cargar todos los cursos disponibles para el select
$cursos_stmt = $curso->readAll(); 

$page_title = "Editar Estudiante: " . htmlspecialchars($estudiante_data['NOMBRE']);
include_once '../views/estudiantes/edit_view.php';
?>