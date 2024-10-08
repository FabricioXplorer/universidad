<?php include '../includes/navbar.php'; ?>
<?php
include '../includes/conexion.php';

$querySolicitudes = "SELECT e.nombre AS nombre_estudiante, e.apellido, e.ci, 
                     m.nombre AS nombre_materia, s.estado, s.imagen, s.fecha_solicitud 
                     FROM solicitudes s
                     JOIN estudiantes e ON s.id_estudiante = e.id_estudiante
                     JOIN materias m ON s.id_materia = m.id_materia";

$resultSolicitudes = $conn->query($querySolicitudes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Solicitudes</title>
    <link rel="stylesheet" href="../includes/style.css"> 
</head>
<body>
    <h1>Listado de Solicitudes</h1>
    <table border="1" class="tablaSolicitudes">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>C.I.</th>
                <th>Materia</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
      
            if ($resultSolicitudes->num_rows > 0) {
          
                while ($row = $resultSolicitudes->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['nombre_estudiante']}</td>
                            <td>{$row['apellido']}</td>
                            <td>{$row['ci']}</td>
                            <td>{$row['nombre_materia']}</td>
                            <td>{$row['estado']}</td>
                            <td><img src='../uploads/{$row['imagen']}' alt='Imagen' width='100'></td>
                            <td>{$row['fecha_solicitud']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay solicitudes registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php

$conn->close();
?>
