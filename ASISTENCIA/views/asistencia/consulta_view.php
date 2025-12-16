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
    <a href="index.php" class="btn btn-outline-primary">⬅️Volver al Panel</a>
    <hr>
    
    <?php if (!empty($message)) echo "<p style='color: red;'>$message</p>"; ?>

    <h2>Seleccionar Clase y Fecha</h2>
    <form action="consulta_asistencia.php" method="post">
        
        <label for="id_horario" class="h5">Horario / Clase:</label>
        <select id="id_horario" name="id_horario" class="form-select w-25" required>
            <option value="">-- Seleccionar --</option>
            <?php 
            // Resetear el puntero del statement si ya se usó antes en el controlador
            $horarios_stmt->execute(); 
            while ($row = $horarios_stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <option 
                    value="<?php echo $row['ID_HORARIO']; ?>"
                    <?php 
                    // Mantener la selección después de enviar el formulario
                    if (isset($_POST['id_horario']) && $_POST['id_horario'] == $row['ID_HORARIO']) {
                        echo 'selected';
                    }
                    ?>
                >
                    <?php echo "{$row['DIA_SEMANA']} {$row['HORA_INICIO']} - {$row['NOMBRE_MATERIA']} ({$row['NOMBRE_CURSO']})"; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="fecha" class="h5">Fecha:</label>
        <input type="date" id="fecha" name="fecha" class="form-control w-25" value="<?php echo htmlspecialchars($fecha_seleccionada); ?>" required><br><br>
        
        <button type="submit" class="btn btn-outline-success">Consultar Asistencia</button>
    </form>

    <hr>
    
    <?php 
    // Mostrar resultados solo si se hizo una consulta exitosa
    if ($asistencia_registrada && $horario_seleccionado): 
    ?>
        <h3>Reporte para: <?php echo "{$horario_seleccionado['NOMBRE_MATERIA']} ({$horario_seleccionado['NOMBRE_CURSO']}) el {$fecha_seleccionada}"; ?></h3>
        
        <?php if ($asistencia_registrada->rowCount() > 0): ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre del Estudiante</th>
                        <th>Documento</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $asistencia_registrada->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['NOMBRE']); ?></td>
                            <td><?php echo htmlspecialchars($row['DOCUMENTO']); ?></td>
                            <td style="font-weight: bold; color: <?php 
                                // Resaltar visualmente el estado
                                if ($row['ESTADO'] == 'FALTÓ') echo 'red';
                                elseif ($row['ESTADO'] == 'TARDANZA') echo 'orange';
                                else echo 'green'; 
                            ?>;">
                                <?php echo htmlspecialchars($row['ESTADO']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['OBSERVACIONES']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontró registro de asistencia para esta clase en la fecha seleccionada.</p>
        <?php endif; ?>

    <?php endif; // Fin del bloque de resultados ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>