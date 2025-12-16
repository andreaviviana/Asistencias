<?php
session_start();
include_once '../includes/auth.php';
// Solo el rol ADMIN puede acceder a esta página
checkRole(['ADMIN']); 

// Incluir las clases necesarias
include_once '../config/Database.php';
include_once '../classes/Materia.php';
include_once '../classes/Docente.php'; // Necesario para el selector de docentes

$database = new Database();
$db = $database->getConnection();

// Instanciar modelos con la conexión ($db)
$materia = new Materia($db);
$docente = new Docente($db);

$message = '';
$materia_data = [];
$id_materia = null;

// 1. Determinar el ID de la materia a editar
if (isset($_GET['id'])) {
    $id_materia = $_GET['id'];
} elseif (isset($_POST['id_materia'])) {
    $id_materia = $_POST['id_materia'];
} else {
    // Redirigir si no hay ID especificado
    header("Location: materias.php?msg=ID_faltante");
    exit();
}

// 2. Procesar el formulario POST (Actualización)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_materia = htmlspecialchars(strip_tags($_POST['nombre']));
    $id_docente = (int)$_POST['id_docente'];

    if ($materia->update($id_materia, $nombre_materia, $id_docente)) {
        // Redirigir al listado después de actualizar
        header("Location: materias.php?action=actualizado");
        exit();
    } else {
        $message = "❌ Error al actualizar la materia. Intente de nuevo.";
    }
}

// 3. Cargar los datos de la materia para precargar el formulario
// Hacemos esto DESPUÉS del POST para recargar los datos si hubo un error de actualización
$materia_data = $materia->readOne($id_materia);

if (!$materia_data) {
    header("Location: materias.php?msg=Materia_no_encontrada");
    exit();
}

// 4. Cargar todos los docentes disponibles para el select
$docentes_stmt = $docente->readAll(); 

$page_title = "Editar Materia: " . htmlspecialchars($materia_data['NOMBRE_MATERIA']);
include_once '../views/materias/edit_view.php';
?>