<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <style>
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .attendance-table th {
            background-color: #f2f2f2;
        }
        .form-container {
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><?php echo $page_title; ?></h1>
        <p>
           <label for="fecha" class="h5"> **Fecha de Registro:** <?php echo htmlspecialchars($fecha_registro); ?></label><br>
            
           <label for="horario" class="h5">
            **Horario:** <?php 
            if (is_array($clase_info)): 
                // Si la clase tiene info, la mostramos.
                echo htmlspecialchars($clase_info['HORA_INICIO'] . " - " . $clase_info['HORA_FIN'] . " (" . $clase_info['DIA_SEMANA'] . ")"); 
            else:
                // Si no tiene info, mostramos un mensaje (aunque el controlador debería redirigir)
                echo "Información de horario no disponible.";
            endif;
            ?>
            </label>
        </p>
        <hr>
        
        <?php if (!empty($message)): ?>
            <div style="padding: 10px; border: 1px solid red; background-color: #ffe6e6; color: #cc0000; margin-bottom: 15px; font-weight: bold;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="tomar_asistencia.php" method="post">
            
            <input type="hidden" name="id_horario" value="<?php echo htmlspecialchars($id_horario); ?>">
            <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($id_curso); ?>">
            
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Estudiante</th>
                        <th>Documento</th>
                        <th style="text-align: center;">Estado de Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    $has_students = false;
                    
                    // ✅ CORRECCIÓN 3: Verificar que $estudiantes_stmt no sea NULL antes de intentar hacer fetch
                    if ($estudiantes_stmt !== null) {
                        // Iterar sobre la lista de estudiantes obtenida
                        while ($row = $estudiantes_stmt->fetch(PDO::FETCH_ASSOC)): 
                            $has_students = true;
                            $id_estudiante = $row['ID_ESTUDIANTE'];
                        ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($row['NOMBRE']); ?></td>
                                <td><?php echo htmlspecialchars($row['DOCUMENTO']); ?></td>
                                <td style="text-align: center;">
                                    <label>
                                        <input type="radio" name="asistencia[<?php echo $id_estudiante; ?>]" value="Presente" required> Presente
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="asistencia[<?php echo $id_estudiante; ?>]" value="Ausente"> Ausente
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="asistencia[<?php echo $id_estudiante; ?>]" value="Tardanza"> Tardanza
                                    </label>
                                </td>
                            </tr>
                        <?php endwhile; 
                    } // Fin de if ($estudiantes_stmt !== null)
                    ?>

                    <?php if (!$has_students): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: red;">
                                <?php echo ($estudiantes_stmt === null) ? "Error al cargar la lista de estudiantes." : "No hay estudiantes registrados para este curso."; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if ($has_students): ?>
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" style="padding: 15px 30px; background-color: #007bff; color: white; border: none; cursor: pointer; font-size: 1.1em;">
                        Guardar Asistencia
                    </button>
                </div>
            <?php endif; ?>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
    </div>
</body>
</html>