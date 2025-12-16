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
    <a href="docentes.php"class="btn btn-outline-primary">⬅️Volver al Listado</a>
    <hr>
    
    <?php if (!empty($message)) echo "<p style='color: red;'>$message</p>"; ?>

    <form action="crear_docente.php" method="post">
        <label for="nombre" class="h5">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" class="form-control w-25" required><br><br>
        
        <label for="documento" class="h5">Documento:</label>
        <input type="text" id="documento" name="documento" class="form-control w-25" required><br><br>
        
        <label for="email" class="h5">Email:</label>
        <input type="email" id="email" name="email" class="form-control w-25" required><br><br>

        <label for="telefono" class="h5">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" class="form-control w-25"><br><br>
        
        <button type="submit" class="btn btn-outline-success">Guardar Docente</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>