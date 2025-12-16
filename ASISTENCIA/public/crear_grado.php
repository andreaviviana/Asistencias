<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// Incluir configuración y modelos
include_once '../config/Database.php';
include_once '../classes/Grado.php'; 

$database = new Database();
$db = $database->getConnection();

$grado = new Grado($db);

$message = '';
$page_title = "Crear Nuevo Grado";

// 1. Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nombre_grado = $_POST['nombre_grado'] ?? '';

    if (!empty($nombre_grado)) {
        if ($grado->create($nombre_grado)) {
            $message = "✅ ¡Grado '$nombre_grado' creado exitosamente!";
            // Opcional: redirigir a la lista de cursos para ver el nuevo grado disponible
        } else {
            $message = "❌ Error al crear el grado. Podría ya existir.";
        }
    } else {
         $message = "❌ El nombre del grado no puede estar vacío.";
    }
}

// 2. Incluir la vista del formulario
// Necesitas crear la carpeta 'views/grados/' y el archivo 'create_view.php'
include_once '../views/grados/create_view.php';
?>