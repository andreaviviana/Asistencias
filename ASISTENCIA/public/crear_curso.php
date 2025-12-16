<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// Incluir configuración y modelos necesarios
include_once '../config/Database.php';
include_once '../classes/Curso.php';
include_once '../classes/Grado.php'; // Necesitamos Grado para el dropdown

$database = new Database();
$db = $database->getConnection();

$curso = new Curso($db);
$grado = new Grado($db); // Crear instancia de Grado

$message = ''; // Variable para mensajes de éxito o error
$page_title = "Crear Nuevo Curso";

// 1. Obtener la lista de Grados para el formulario (dropdown)
// Nota: Asume que tienes un método readAll() básico en classes/Grado.php
$stmt_grados = $grado->readAll(); 

// 2. Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Obtener y sanear datos del POST
    $nombre_curso = $_POST['nombre_curso'] ?? '';
    $id_grado = $_POST['id_grado'] ?? '';

    // Llamar al método create (asumimos que existe en Curso.php)
    if ($curso->create($nombre_curso, $id_grado)) {
        $message = "✅ ¡Curso creado exitosamente!";
        // Opcional: Redirigir a la lista de cursos después de crear
        // header('Location: cursos.php?success=1');
        // exit;
    } else {
        $message = "❌ Error al crear el curso. Verifique los datos.";
    }
}

// 3. Incluir la vista del formulario
include_once '../views/cursos/create_view.php';
?>