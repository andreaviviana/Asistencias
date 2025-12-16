<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <style>
        /* Estilos básicos si no estás usando Bootstrap */
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1><?php echo $page_title; ?></h1>
        <a href="index.php" class="btn btn-secondary mb-3">Volver al Panel</a>
        <hr>
        
        <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

        <?php if ($clase_info): // Usamos $clase_info que debe venir del controlador tomar_asistencia.php ?>
            <h2>Clase: <?php echo htmlspecialchars($clase_info['NOMBRE_MATERIA']); ?> (<?php echo htmlspecialchars($clase_info['NOMBRE_CURSO']); ?>)</h2>
            <p>
                **Fecha de Registro:** <span class="badge bg-primary"><?php echo htmlspecialchars($fecha_registro ?? 'N/A'); ?></span>
                <br>Marque el estado de cada estudiante y haga clic en Guardar.
            </p>

            <?php if (isset($estudiantes_stmt) && $estudiantes_stmt->rowCount() > 0): ?>
                <form action="tomar_asistencia.php" method="post">
                    
                    <input type="hidden" name="id_horario" value="<?php echo htmlspecialchars($id_horario); ?>">
                    <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($id_curso); ?>">
                    <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha_registro); ?>"> 
                    
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre del Estudiante</th>
                                <th>Estado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $counter = 1;
                            while ($row = $estudiantes_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($row['NOMBRE']); ?></td>
                                    <td>
                                        <select name="asistencia[<?php echo $row['ID_ESTUDIANTE']; ?>][estado]" class="form-select" required>
                                            <option value="ASISTIÓ">ASISTIÓ</option>
                                            <option value="FALTÓ">FALTÓ</option>
                                            <option value="TARDANZA">TARDANZA</option>
                                            <option value="JUSTIFICADO">JUSTIFICADO</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="asistencia[<?php echo $row['ID_ESTUDIANTE']; ?>][observaciones]" class="form-control" placeholder="Opcional">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Guardar Asistencia</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    No hay estudiantes registrados en este curso.
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-info">
                No se pudo cargar la información de la clase.
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>