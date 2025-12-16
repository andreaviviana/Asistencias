<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
</head>
<body>
    <h1>Panel de Control de Docentes</h1>
    <p><a href="logout.php">Cerrar Sesión</a></p>
    <hr>
    
    <?php if (isset($_GET['action']) && $_GET['action'] == 'asistencia_registrada'): ?>
        <div style="padding: 10px; border: 1px solid green; background-color: #e6ffe6; color: #006600; font-weight: bold; margin-bottom: 15px;">
            ✅ ¡Asistencia registrada con éxito!
        </div>
    <?php endif; ?>

    <?php if ($clase_activa): ?>
        
        <div style="padding: 15px; border: 2px solid #007bff; background-color: #eaf4ff; margin-bottom: 20px;">
            <h3>Clase Activa en este Momento</h3>
            <p style="font-size: 1.2em; font-weight: bold;">
                Materia: <?php echo htmlspecialchars($clase_activa['NOMBRE_MATERIA']); ?><br>
                Curso: <?php echo htmlspecialchars($clase_activa['NOMBRE_CURSO']); ?>
            </p>
        </div>

        <?php if ($asistencia_tomada): ?>
            <div style="padding: 10px; border: 1px solid green; background-color: #e6ffe6; color: #006600; font-weight: bold;">
                ✅ ¡La asistencia ya fue registrada hoy!
            </div>
        <?php else: ?>
            
            <a href="tomar_asistencia.php?id_horario=<?php echo htmlspecialchars($clase_activa['ID_HORARIO']); ?>&id_curso=<?php echo htmlspecialchars($clase_activa['ID_CURSO']); ?>">
                <button style="padding: 15px 30px; background-color: #4CAF50; color: white; border: none; cursor: pointer; font-size: 1.1em;">
                    🔴 Iniciar Toma de Asistencia Ahora
                </button>
            </a>
            
        <?php endif; ?>

    <?php else: ?>
        <div style="padding: 15px; border: 1px solid #777; background-color: #f2f2f2; color: #333;">
            <p style="font-weight: bold;">Actualmente no tiene ninguna clase programada.</p>
        </div>
    <?php endif; ?>
    
    <hr style="margin-top: 30px;">
    
    <h3>Reportes</h3>
    <ul>
        <li><a href="consulta_asistencia.php">Consultar Reportes de Asistencia</a></li>
    </ul>
    
</body>
</html>