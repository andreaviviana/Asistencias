<?php
session_start();
include_once '../includes/auth.php';
// Solo el rol ADMIN puede acceder a esta página
checkRole(['ADMIN']); 

// Incluir las clases necesarias
include_once '../config/Database.php';
include_once '../classes/Horario.php';
include_once '../classes/Materia.php'; // Necesario para el selector de materias
include_once '../classes/Curso.php';   // Necesario para el selector de cursos

$database = new Database();
$db = $database->getConnection();

// Instanciar modelos con la conexión ($db)
$horario = new Horario($db);
$materia = new Materia($db);
$curso = new Curso($db);

$message = '';
$horario_data = [];
$id_horario = null;
$dias_semana = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES']; // Días fijos para el ENUM

// 1. Determinar el ID del horario a editar
if (isset($_GET['id'])) {
    $id_horario = $_GET['id'];
} elseif (isset($_POST['id_horario'])) {
    $id_horario = $_POST['id_horario'];
} else {
    // Redirigir si no hay ID especificado
    header("Location: horarios.php?msg=ID_faltante");
    exit();
}

// 2. Procesar el formulario POST (Actualización)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanear todos los datos, incluido el ID oculto
    $id_materia = (int)$_POST['id_materia'];
    $id_curso = (int)$_POST['id_curso'];
    $dia_semana = htmlspecialchars(strip_tags($_POST['dia_semana']));
    $hora_inicio = htmlspecialchars(strip_tags($_POST['hora_inicio']));
    $hora_fin = htmlspecialchars(strip_tags($_POST['hora_fin']));

    // Llamar a la función update
    if ($horario->update($id_horario, $id_materia, $id_curso, $dia_semana, $hora_inicio, $hora_fin)) {
        // Redirigir al listado después de actualizar
        header("Location: horarios.php?action=actualizado");
        exit();
    } else {
        $message = "❌ Error al actualizar el horario. Verifique la validez de los datos.";
    }
}

// 3. Cargar los datos del horario para precargar el formulario
// Esto se hace DESPUÉS del POST para recargar los datos si hubo un error de actualización
$horario_data = $horario->readOne($id_horario);

if (!$horario_data) {
    header("Location: horarios.php?msg=Horario_no_encontrado");
    exit();
}

// 4. Cargar todos los datos para los selectores
$materias_stmt = $materia->readAll(); 
$cursos_stmt = $curso->readAll(); 

$page_title = "Editar Horario: " . htmlspecialchars($horario_data['NOMBRE_MATERIA']) . " (" . htmlspecialchars($horario_data['NOMBRE_CURSO']) . ")";
include_once '../views/horarios/edit_view.php';
?>