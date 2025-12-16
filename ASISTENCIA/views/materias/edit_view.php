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
    <a href="materias.php"class="btn btn-outline-primary">⬅️Volver al Listado</a>
    <hr>
    
    <?php if (!empty($message)) echo "<p style='color: red; font-weight: bold;'>$message</p>"; ?>

    <form action="editar_materia.php" method="post">
        
        <input type="hidden" name="id_materia" value="<?php echo htmlspecialchars($materia_data['ID_MATERIA']); ?>">
        
        <label for="nombre" class="h5">Nombre de la Materia:</label>
        <input type="text" id="nombre" name="nombre" class="form-control w-25"
               value="<?php echo htmlspecialchars($materia_data['NOMBRE_MATERIA']); ?>" required><br><br>
        
        <label for="id_docente"class="h5">Docente Asignado:</label>
        <select id="id_docente" name="id_docente" class="form-select w-25" required>
            <option value="">-- Seleccione un Docente --</option>
            
            <?php 
            // 1. Obtener el ID del docente actual de la materia
            $current_docente_id = $materia_data['ID_DOCENTE'];
            
            // 2. Iterar sobre todos los docentes disponibles
            while ($row = $docentes_stmt->fetch(PDO::FETCH_ASSOC)): 
                // 3. Determinar si este docente debe estar seleccionado
                $selected = ($row['ID_DOCENTE'] == $current_docente_id) ? 'selected' : '';
            ?>
                <option value="<?php echo htmlspecialchars($row['ID_DOCENTE']); ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($row['NOMBRE']); ?>
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