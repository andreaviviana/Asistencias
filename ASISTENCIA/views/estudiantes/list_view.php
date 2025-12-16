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
    <a href="index.php"class="btn btn-outline-primary">⬅️Volver al Panel</a> 
    
    <a href="crear_estudiante.php"class="btn btn-outline-info">Crear Nuevo Estudiante</a>
    <hr>
    
    <?php if (!empty($message)): ?>
        <div style="padding: 10px; border: 1px solid green; background-color: #e6ffe6; color: #006600; margin-bottom: 15px;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Grado</th>
                <th>Curso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // 2. Iterar sobre los resultados obtenidos del controlador ($stmt)
            if (isset($stmt) && $stmt->rowCount() > 0): 
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($row['DOCUMENTO']); ?></td>
                    <td><?php echo htmlspecialchars($row['NOMBRE_GRADO']); ?></td> 
                    <td><?php echo htmlspecialchars($row['NOMBRE_CURSO']); ?></td>
                    <td>
                        <a href="editar_estudiante.php?id=<?php echo $row['ID_ESTUDIANTE']; ?>"class="btn btn-outline-warning">Editar</a> | 
                        <a href="eliminar_estudiante.php?id=<?php echo $row['ID_ESTUDIANTE']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este estudiante?');"class="btn btn-outline-danger">Eliminar</a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="5">No hay estudiantes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>