<?php
session_start();
include_once '../includes/auth.php';
checkRole(['ADMIN']); 

// 1. Incluir la clase de Base de Datos y configuración
include_once '../config/Database.php'; 
include_once '../classes/Docente.php';

// Inicialización de la base de datos
$database = new Database();
$db = $database->getConnection();

// 2. Instanciar el modelo con la conexión $db
$docente = new Docente($db);
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Obtener y sanear los datos del POST
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $telefono = htmlspecialchars(strip_tags($_POST['telefono']));
    $documento = htmlspecialchars(strip_tags($_POST['documento']));

    // 4. Llamar a la función create
    if ($docente->create($nombre, $email, $telefono, $documento)) {
        // Redirección de ÉXITO (action=creado)
        header("Location: docentes.php?action=creado");
        exit();
    } else {
        // Si falla, se muestra el mensaje de error en la vista
        $message = "Error al crear el docente. El email o documento pueden ya estar registrados. Verifique la base de datos.";
    }
}

$page_title = "Crear Nuevo Docente";
include_once '../views/docentes/create_view.php';
?>