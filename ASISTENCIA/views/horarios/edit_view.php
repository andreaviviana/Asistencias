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
    <a href="horarios.php"class="btn btn-outline-primary">⬅️Volver al Listado</a>
    <hr>
    
    <?php if (!empty($message)) echo "<p style='color: red; font-weight: bold;'>$message</p>"; ?>

    <form action="editar_horario.php" method="post">
        
        <input type="hidden" name="id_horario" value="<?php echo htmlspecialchars($horario_data['ID_HORARIO']); ?>">
        
        <h3>Definición de Tiempo</h3>
        
        <label for="dia_semana"class="h5">Día de la Semana:</label>
        <select id="dia_semana" name="dia_semana" class="form-select w-25" required>
            <?php 
            $current_dia = $horario_data['DIA_SEMANA'];
            foreach ($dias_semana as $dia): 
                $selected = ($dia == $current_dia) ? 'selected' : '';
            ?>
                <option value="<?php echo htmlspecialchars($dia); ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($dia); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label for="hora_inicio"class="h5">Hora de Inicio:</label>
        <input type="time" id="hora_inicio" name="hora_inicio" class="form-control w-25"
               value="<?php echo htmlspecialchars($horario_data['HORA_INICIO']); ?>" required><br><br>

        <label for="hora_fin"class="h5">Hora de Fin:</label>
        <input type="time" id="hora_fin" name="hora_fin" class="form-control w-25"
               value="<?php echo htmlspecialchars($horario_data['HORA_FIN']); ?>" required><br><br>
        
        <hr>
        
        <h3>Asignación</h3>

        <label for="id_materia"class="h5">Materia y Docente:</label>
        <select id="id_materia" name="id_materia" class="form-select w-25" required>
            <option value="">-- Seleccione una Materia --</option>
            <?php 
            $current_materia_id = $horario_data['ID_MATERIA'];
            // Se asume que $materias_stmt fue recargado en el controlador después del POST
            $materias_stmt->execute(); // Reiniciar el statement si es necesario
            while ($row = $materias_stmt->fetch(PDO::FETCH_ASSOC)): 
                $selected = ($row['ID_MATERIA'] == $current_materia_id) ? 'selected' : '';
            ?>
                <option value="<?php echo htmlspecialchars($row['ID_MATERIA']); ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($row['NOMBRE_MATERIA']) . " (" . htmlspecialchars($row['NOMBRE_DOCENTE']) . ")"; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <label for="id_curso"class="h5">Curso y Grado:</label>
        <select id="id_curso" name="id_curso" class="form-select w-25" required>
            <option value="">-- Seleccione un Curso --</option>
            <?php 
            $current_curso_id = $horario_data['ID_CURSO'];
            // Se asume que $cursos_stmt fue recargado en el controlador después del POST
            $cursos_stmt->execute(); // Reiniciar el statement si es necesario
            while ($row = $cursos_stmt->fetch(PDO::FETCH_ASSOC)): 
                $selected = ($row['ID_CURSO'] == $current_curso_id) ? 'selected' : '';
            ?>
                <option value="<?php echo htmlspecialchars($row['ID_CURSO']); ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($row['NOMBRE_CURSO']) . " (" . htmlspecialchars($row['NOMBRE_GRADO']) . ")"; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <button type="submit" class="btn btn-outline-success">Guardar Cambios</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>