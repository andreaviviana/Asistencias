<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// Incluir configuración y modelos
include_once '../config/Database.php'; // 1. ¡INCLUIDO!
include_once '../classes/Horario.php';
include_once '../classes/Materia.php'; // Incluye Materia (necesario para el select)
include_once '../classes/Curso.php'; // Incluye Curso (necesario para el select)
// NOTA: Docente.php no es necesario si solo usamos Materia::readAll()

// Inicialización de la base de datos
$database = new Database();
$db = $database->getConnection();

// 2. Instanciar modelos con la conexión ($db)
$horario = new Horario($db);
$materia = new Materia($db);
$curso = new Curso($db);

// Obtener datos para los dropdowns de la vista
// NOTA: Materia::readAll() ya incluye el nombre del docente, lo cual es útil.
$materias_stmt = $materia->readAll();
$cursos_stmt = $curso->readAll();

// Definición de días para la vista (ENUM en español)
$dias_semana = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Obtener y sanear los datos
    $id_materia = (int)$_POST['id_materia'];
    $id_curso = (int)$_POST['id_curso'];
    $dia_semana = htmlspecialchars(strip_tags($_POST['dia_semana']));
    $hora_inicio = htmlspecialchars(strip_tags($_POST['hora_inicio']));
    $hora_fin = htmlspecialchars(strip_tags($_POST['hora_fin']));

    // 4. Llamar a create() SIN $id_docente
    if ($horario->create($id_materia, $id_curso, $dia_semana, $hora_inicio, $hora_fin)) {
        header("Location: horarios.php?action=creado");
        exit();
    } else {
        $message = "❌ Error al crear el horario. Verifique que no haya conflictos o datos faltantes. (Asegúrese de que el ID de Materia y Curso existen).";
        
        // Recargar statements en caso de error para que la vista tenga los datos
        $materias_stmt = $materia->readAll();
        $cursos_stmt = $curso->readAll();
    }
}

$page_title = "Crear Nuevo Horario";

// Cargar la vista de creación
include_once '../views/horarios/create_view.php';