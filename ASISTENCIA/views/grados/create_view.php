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
    <p><a href="cursos.php"class="btn btn-outline-primary">⬅️ Volver a la Gestión de Cursos/Grados</a></p>
    <hr>
    
    <?php if (!empty($message)): ?>
        <?php 
            // Determina el estilo (verde para éxito '✅', rojo para error '❌')
            $is_error = strpos($message, '❌') !== false;
            $style = $is_error 
                ? "padding: 10px; border: 1px solid red; background-color: #ffe6e6; color: #cc0000; margin-bottom: 15px; font-weight: bold;"
                : "padding: 10px; border: 1px solid green; background-color: #e6ffe6; color: #006600; margin-bottom: 15px; font-weight: bold;";
        ?>
        <div style="<?php echo $style; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="crear_grado.php" method="POST">
        
        <label for="nombre_grado"class="h5">Nombre del Nuevo Grado:</label>
        <input type="text" id="nombre_grado" name="nombre_grado" class="form-control w-25" required><br><br>

        <button type="submit"class="btn btn-outline-success">Guardar Grado</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>