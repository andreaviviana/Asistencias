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
    <a href="cursos.php"class="btn btn-outline-primary">⬅️Volver al Listado</a>
    <hr>

    <?php if (!empty($message)) echo "<p style='color: red; font-weight: bold;'>$message</p>"; ?>

    <form action="editar_curso.php" method="post">
        <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($curso_data['ID_CURSO']); ?>">
        
        <label for="nombre_curso"class="h5">Nombre del Curso:</label>
        <input type="text" id="nombre_curso" name="nombre_curso" class="form-control w-25"
               value="<?php echo htmlspecialchars($curso_data['NOMBRE_CURSO']); ?>" required><br><br>

        <label for="id_grado"class="h5">Grado Asociado:</label>
        <select id="id_grado" name="id_grado" class="form-select w-25" required>
            <option value="">-- Seleccione un Grado --</option>
            <?php 
            $current_grado_id = $curso_data['ID_GRADO'];
            // Aseguramos que el statement se ejecute de nuevo si ya se usó antes
            $grados_stmt->execute(); 
            while ($row = $grados_stmt->fetch(PDO::FETCH_ASSOC)): 
                $selected = ($row['ID_GRADO'] == $current_grado_id) ? 'selected' : '';
            ?>
                <option value="<?php echo htmlspecialchars($row['ID_GRADO']); ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($row['NOMBRE_GRADO']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <button type="submit"class="btn btn-outline-success">Guardar Cambios</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>