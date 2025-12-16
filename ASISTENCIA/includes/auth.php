<?php
// Nota: session_start() debe ser llamado en la página que incluye este archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Si no hay sesión, redirigir al login
    header("Location: login.php");
    exit();
}

// Función para verificar roles
function checkRole($allowed_roles) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $allowed_roles)) {
        // Redirigir a un panel si no tiene permisos
        header("Location: index.php?error=Acceso Denegado");
        exit();
    }
}
?>