<?php include '../includes/navbar.php'; ?>
<?php
include '../includes/conexion.php';

// Consulta para obtener los datos de asignaciones con los nombres correspondientes
$queryAsignaciones = "SELECT m.nombre AS materia, d.nombre AS nombre_docente, 
                      a.nombre_aula AS aula, asign.turno, asign.fecha_asignacion 
                      FROM asignaciones AS asign 
                      JOIN materias AS m ON asign.id_materia = m.id_materia 
                      JOIN docentes AS d ON asign.id_docente = d.id_docente 
                      JOIN aulas AS a ON asign.id_aula = a.id_aula";

$resultAsignaciones = $conn->query($queryAsignaciones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Asignaciones</title>
    <link rel="stylesheet" href="../includes/style.css"> 
</head>
<body>
    <h1>Listado de Asignaciones</h1>
    <table border="1" class="tablaAsign">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Docente</th>
                <th>Aula</th>
                <th>Turno</th>
                <th>Fecha de Asignación</th>
            </tr>
        </thead>
        <tbody>
            <?php
        
            if ($resultAsignaciones->num_rows > 0) {
        
                while ($row = $resultAsignaciones->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['materia']}</td>
                            <td>{$row['nombre_docente']}</td>
                            <td>{$row['aula']}</td>
                            <td>{$row['turno']}</td>
                            <td>{$row['fecha_asignacion']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay asignaciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php

$conn->close();
?>
