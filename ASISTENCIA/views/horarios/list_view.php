<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
</head>
<body>
    <h1><?php echo $page_title; ?></h1>
    <a href="index.php"class="btn btn-outline-primary">⬅️Volver al Panel</a> 
    <a href="crear_horario.php"class="btn btn-outline-info">Añadir Horario</a>
    <hr>
    
   <?php if (!empty($message)): ?>
        <?php 
            $is_error = strpos($message, '❌') !== false;
            $style = $is_error 
                ? "padding: 10px; border: 1px solid red; background-color: #ffe6e6; color: #cc0000; margin-bottom: 15px; font-weight: bold;"
                : "padding: 10px; border: 1px solid green; background-color: #e6ffe6; color: #006600; margin-bottom: 15px; font-weight: bold;";
        ?>
        <div style="<?php echo $style; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($stmt->rowCount() > 0): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Docente</th>
                    <th>Materia</th>
                    <th>Curso/Grado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['DIA_SEMANA']); ?></td>
                        <td><?php echo date('H:i', strtotime($row['HORA_INICIO'])); ?></td>
                        <td><?php echo date('H:i', strtotime($row['HORA_FIN'])); ?></td>
                        <td><?php echo htmlspecialchars($row['NOMBRE_DOCENTE']); ?></td>
                        <td><?php echo htmlspecialchars($row['NOMBRE_MATERIA']); ?></td>
                        <td><?php echo htmlspecialchars($row['NOMBRE_CURSO']) . " (" . htmlspecialchars($row['NOMBRE_GRADO']) . ")"; ?></td>
                        <td>
                            <a href="editar_horario.php?id=<?php echo $row['ID_HORARIO']; ?>"class="btn btn-outline-warning">Editar</a> | 
                            <a href="eliminar_horario.php?id=<?php echo $row['ID_HORARIO']; ?>" onclick="return confirm('¿Eliminar este horario?');"class="btn btn-outline-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay horarios registrados.</p>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>