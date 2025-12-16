<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// Incluir configuración y modelos
include_once '../config/Database.php'; // Agregado
include_once '../classes/Estudiante.php';
include_once '../classes/Curso.php'; 

$database = new Database(); 
$db = $database->getConnection();

// Instanciar modelos con la conexión ($db)
$estudiante = new Estudiante($db);
$curso = new Curso($db); 

$cursos_stmt = $curso->readAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Es buena práctica sanear la entrada
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $documento = htmlspecialchars(strip_tags($_POST['documento']));
    $fecha_nacimiento = $_POST['fecha_nacimiento']; // Ya es formato de fecha
    $id_curso = (int)$_POST['id_curso'];

    // Asegúrate de que el método create en Estudiante.php acepte estos 4 parámetros
    if ($estudiante->create($nombre, $documento, $fecha_nacimiento, $id_curso)) {
        // Redirigir usando 'creado' (el valor que espera estudiantes.php)
        header("Location: estudiantes.php?action=creado");
        exit();
    } else {
        $message = "Error al crear el estudiante. Intente de nuevo o verifique si el documento ya existe.";
        // Si hay error, necesitamos recargar los cursos para que la vista funcione correctamente:
        $cursos_stmt = $curso->readAll(); 
    }
} else {
    $message = ''; // Inicializar mensaje si es GET
}

$page_title = "Crear Nuevo Estudiante";
include_once '../views/estudiantes/create_view.php';
?>