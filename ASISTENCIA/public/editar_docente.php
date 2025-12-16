<?php
session_start();
include_once '../includes/auth.php';
// Requerir que el usuario tenga rol 'ADMIN'
checkRole(['ADMIN']); 

// Incluir las clases necesarias
include_once '../config/Database.php';
include_once '../classes/Docente.php'; // Cambiado de Estudiante a Docente
// include_once '../classes/Curso.php'; // No es necesario para Docentes


$database = new Database();
$db = $database->getConnection();

// Instanciar el modelo Docente
$docente = new Docente($db); // Usamos el objeto Docente
// El curso ya no es necesario

$message = '';
$docente_data = []; // Cambiamos el nombre de la variable de datos
$id_docente = null; // Cambiamos el nombre del ID

// 1. Determinar el ID del docente a editar (GET o POST)
if (isset($_GET['id'])) {
    $id_docente = $_GET['id'];
} elseif (isset($_POST['id_docente'])) { // El formulario POST enviará id_docente
    $id_docente = $_POST['id_docente'];
} else {
    // Redirigir si no hay ID especificado
    header("Location: docentes.php?msg=ID_faltante"); // Redirige a docentes.php
    exit();
}

// 2. Procesar el formulario POST (Actualización)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir solo los campos del docente (Nombre, Documento, Email, Teléfono)
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $documento = htmlspecialchars(strip_tags($_POST['documento']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $telefono = htmlspecialchars(strip_tags($_POST['telefono']));
    
    // NOTA: Asegúrate de que Docente::update acepte estos 5 parámetros: ID, Nombre, Email, Teléfono, Documento
    if ($docente->update($id_docente, $nombre, $email, $telefono, $documento)) {
        // Redirigir al listado de DOCENTES con el mensaje de éxito
        header("Location: docentes.php?action=actualizado");
        exit();
    } else {
        $message = "❌ Error al actualizar el docente. El email o documento pueden ya existir.";
    }
}

// 3. Cargar los datos del docente para precargar el formulario
// Hacemos esto DESPUÉS del POST para recargar los datos actualizados si hubo un error de mensaje
$docente_data = $docente->readOne($id_docente);

if (!$docente_data) {
    header("Location: docentes.php?msg=Docente_no_encontrado");
    exit();
}

// 4. Incluir la vista de edición
// Ya no necesitamos cargar cursos, así que pasamos directamente a la vista
$page_title = "Editar Docente: " . htmlspecialchars($docente_data['NOMBRE']);
include_once '../views/docentes/edit_view.php';
?>