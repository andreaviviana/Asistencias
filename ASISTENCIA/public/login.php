<?php
session_start();
include_once '../classes/Usuario.php';

// Redirigir si ya está logueado
if (isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirigir al panel principal
    exit;
}

$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = new Usuario();
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Nota: Es mejor limpiar el username aquí también antes de pasarlo al modelo
    $username = htmlspecialchars(strip_tags($username)); 

    $user_data = $usuario->login($username, $password);

    if ($user_data) {
        // Establecer variables de sesión
        $_SESSION['user_id'] = $user_data['ID_USUARIO'];
        $_SESSION['username'] = $user_data['USERNAME'];
        $_SESSION['rol'] = $user_data['ROL'];
        
        // ***** AJUSTE CRÍTICO: Guardar el ID_DOCENTE en la sesión *****
        // El ID_DOCENTE es necesario para saber qué clase puede tomar asistencia.
        if ($user_data['ROL'] === 'DOCENTE' && !empty($user_data['ID_DOCENTE'])) {
            $_SESSION['id_docente'] = $user_data['ID_DOCENTE'];
        }
        // *************************************************************
        
        // Redirección exitosa
        header('Location: index.php');
        exit;
    } else {
        $error_message = 'Usuario o contraseña incorrectos.';
    }
}
?>
<?php include_once '../views/login_view.php'; ?>