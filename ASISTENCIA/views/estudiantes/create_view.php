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
    <a href="estudiantes.php"class="btn btn-outline-primary">⬅️Volver al Listado</a>
    <hr>
    
    <?php if (isset($message) && !empty($message)): ?>
        <p style='color: red; font-weight: bold;'><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="crear_estudiante.php" method="post">
        
        <label for="nombre" class="h5">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" class="form-control w-25" required><br><br>
        
        <label for="documento" class="h5">Documento:</label>
        <input type="number" id="documento" name="documento" class="form-control w-25" required><br><br>
        
        <label for="fecha_nacimiento" class="h5">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control w-25" required><br><br>
        
        <label for="id_curso" class="h5">Curso:</label>
        <select id="id_curso" name="id_curso" class="form-select w-25" required>
            <option value="">-- Seleccione un Curso --</option>
            
            <?php 
            // La variable $cursos_stmt debe venir del controlador public/crear_estudiante.php
            while ($row = $cursos_stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <option value="<?php echo htmlspecialchars($row['ID_CURSO']); ?>">
                    <?php echo htmlspecialchars($row['NOMBRE_CURSO']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <button type="submit" class="btn btn-outline-success">Guardar Estudiante</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>