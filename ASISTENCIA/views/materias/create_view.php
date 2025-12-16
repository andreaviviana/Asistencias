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

    <form action="crear_materia.php" method="post">
        
        <label for="nombre"class="h5">Nombre de la Materia:</label>
        <input type="text" id="nombre" name="nombre" class="form-control w-25" required><br><br>
        
        <label for="id_docente" class="h5">Docente Asignado:</label>
        <select id="id_docente" name="id_docente" class="form-select w-25" required>
            <option value="">-- Seleccione un Docente --</option>
            
            <?php 
            // La variable $docentes_stmt viene del controlador public/crear_materia.php
            // Se asume que el Docente tiene las columnas ID_DOCENTE y NOMBRE
            while ($row = $docentes_stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <option value="<?php echo htmlspecialchars($row['ID_DOCENTE']); ?>">
                    <?php echo htmlspecialchars($row['NOMBRE']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>
        
        <button type="submit"class="btn btn-outline-success">Guardar Materia</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>