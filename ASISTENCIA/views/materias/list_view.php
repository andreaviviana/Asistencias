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
    <a href="crear_materia.php"class="btn btn-outline-info">Crear Nueva Materia</a>
    <hr>
    
    <?php if (!empty($message)): ?>
        <div style="padding: 10px; border: 1px solid green; background-color: #e6ffe6; color: #006600; margin-bottom: 15px; font-weight: bold;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($stmt->rowCount() > 0): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de la Materia</th>
                    <th>Docente Asignado</th> 
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID_MATERIA']); ?></td>
                        <td><?php echo htmlspecialchars($row['NOMBRE_MATERIA']); ?></td>
                        <td><?php echo htmlspecialchars($row['NOMBRE_DOCENTE']); ?></td>
                        <td>
                            <a href="editar_materia.php?id=<?php echo $row['ID_MATERIA']; ?>"class="btn btn-outline-warning">Editar</a> | 
                            <a href="eliminar_materia.php?id=<?php echo $row['ID_MATERIA']; ?>" 
                               onclick="return confirm('¿Seguro que quieres eliminar esta materia? Esto podría afectar los registros de HORARIO y ASISTENCIA.');"class="btn btn-outline-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay materias registradas.</p>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>