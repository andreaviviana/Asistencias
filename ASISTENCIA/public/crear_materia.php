<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// 1. Incluir configuración y modelos necesarios
include_once '../config/Database.php';
include_once '../classes/Materia.php';
include_once '../classes/Docente.php'; // Necesario para listar docentes disponibles

// Inicialización de la base de datos
$database = new Database();
$db = $database->getConnection();

// 2. Instanciar modelos con la conexión ($db)
$materia = new Materia($db);
$docente = new Docente($db); 

$message = '';

// Obtener todos los docentes para el dropdown de la vista
$docentes_stmt = $docente->readAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Obtener y sanear los datos del POST
    $nombre_materia = htmlspecialchars(strip_tags($_POST['nombre']));
    $id_docente = (int)$_POST['id_docente']; // Asegúrate de recibir este campo del formulario

    // 4. Llamar a la función create con AMBOS parámetros
    // Materia::create($nombre_materia, $id_docente)
    if ($materia->create($nombre_materia, $id_docente)) {
        header("Location: materias.php?action=creado");
        exit();
    } else {
        // Mejorar el mensaje de error para dar más detalles
        $message = "❌ Error al crear la materia. Intente de nuevo.";
        // Si falla el guardado, recargar la lista de docentes por si acaso
        $docentes_stmt = $docente->readAll();
    }
}

$page_title = "Crear Nueva Materia";
// Cargar la vista de creación
include_once '../views/materias/create_view.php';
?>