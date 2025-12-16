<?php
// Inicia la sesión
session_start();
// Incluye el control de autenticación para proteger la página
include_once '../includes/auth.php'; 

$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel de Control</title>
    <style>
        body {
            margin: 0; /* Elimina el margen por defecto del body */
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        /* --- ESTILOS DEL HEADER FIJO (SIN CAMBIOS) --- */
        .header-fijo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #2196F3;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            box-sizing: border-box;
        }
        .header-fijo h1 {
            margin: 0;
            font-size: 1.5em;
            display: inline-block;
        }
        .header-fijo a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }
        
        /* --- ESTILOS DEL CONTENIDO Y BOTONES (MEJORA DE DISEÑO) --- */
        .contenido-principal {
            margin-top: 80px; /* Margen para el header fijo */
            padding: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .seccion-opciones {
            margin-bottom: 40px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .opciones-grid {
            display: flex; /* Activa Flexbox para colocar los botones en línea */
            flex-wrap: wrap; /* Permite que los botones pasen a la siguiente línea */
            gap: 20px; /* Espacio entre los botones/tarjetas */
            padding: 0;
            list-style: none; /* Elimina los puntos de la lista */
        }

        .opcion-item {
            flex: 1 1 200px; /* Base para que cada item ocupe al menos 200px de ancho */
            min-width: 220px; /* Ancho mínimo para mantener la forma */
        }
        
        .opcion-item a {
            display: block;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
            color: #333;
            background-color: #e3f2fd; /* Fondo azul muy claro */
            border: 1px solid #bbdefb; /* Borde azul claro */
        }

        .opcion-item a:hover {
            background-color: #bbdefb; /* Fondo un poco más oscuro al pasar el ratón */
            transform: translateY(-2px); /* Efecto de elevación */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Estilo específico para los botones de Asistencia (Acción Principal) */
        .opciones-asistencia .opcion-item a {
            background-color: #c8e6c9; /* Verde claro */
            border-color: #a5d6a7; /* Borde verde */
        }
        
        .opciones-asistencia .opcion-item a:hover {
            background-color: #a5d6a7;
        }
        
    </style>
</head>
<body>
    
    <div class="header-fijo">
        <h1>Panel Principal (Rol: <?php echo htmlspecialchars($rol); ?>)</h1>
        <span style="float: right;">
            <a href="logout.php">Cerrar Sesión</a>
        </span>
    </div>
    
    <div class="contenido-principal">
    
        <?php if ($rol == 'ADMIN'): ?>
            <div class="seccion-opciones">
                <h2>Opciones de Administración</h2>
                <ul class="opciones-grid">
                    <li class="opcion-item"><a href="cursos.php">Gestionar Cursos y Grados</a></li>
                    <li class="opcion-item"><a href="docentes.php">Gestionar Docentes (CRUD)</a></li>
                    <li class="opcion-item"><a href="materias.php">Gestion Materias (CRUD)</a></li>
                    <li class="opcion-item"><a href="estudiantes.php">Gestionar Estudiantes (CRUD)</a></li> 
                    <li class="opcion-item"><a href="horarios.php">Horarios</a></li>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($rol == 'DOCENTE' || $rol == 'ADMIN'): ?>
            <div class="seccion-opciones opciones-asistencia">
                <h2>Opciones de Asistencia</h2>
                <ul class="opciones-grid">
                    <li class="opcion-item"><a href="../views/asistencia/tomar_asistencia_view.php">Tomar Asistencia del Día</a></li>
                    <li class="opcion-item"><a href="consulta_asistencia.php">Ver Reportes</a></li>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>