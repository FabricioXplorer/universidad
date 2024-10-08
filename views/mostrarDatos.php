<?php
include '../includes/conexion.php';

$queryMaterias = "SELECT id_materia, nombre FROM materias";
$resultMaterias = $conn->query($queryMaterias);

$queryDocentes = "
    SELECT d.id_docente, d.nombre, d.apellido, d.profesion 
    FROM docentes d";
$resultDocentes = $conn->query($queryDocentes);

$queryAulas = "SELECT id_aula, nombre_aula FROM aulas";
$resultAulas = $conn->query($queryAulas);
$aulas = $resultAulas->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/styles.css">
    <title>Asignaciones</title>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<h1>Asignaciones</h1>

<table border="1">
    <tr>
        <th>Materia</th>
        <th>Docente</th>
        <th>Aula</th>
        <th>Turno</th>
        <th>Fecha de Asignación</th>
        <th>Acción</th>
    </tr>
    <tr>
        <form action="guardar_asignacion.php" method="POST">

            <td>
                <select name="materia" required>
                    <option value="">-- Selecciona una Materia --</option>
                    <?php while ($rowMateria = $resultMaterias->fetch_assoc()): ?>
                        <option value="<?php echo $rowMateria['id_materia']; ?>">
                            <?php echo htmlspecialchars($rowMateria['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>


            <td>
                <select name="docente" required>
                    <option value="">-- Selecciona un Docente --</option>
                    <?php while ($rowDocente = $resultDocentes->fetch_assoc()): ?>
                        <option value="<?php echo $rowDocente['id_docente']; ?>">
                            <?php echo htmlspecialchars($rowDocente['profesion'] . ' ' . $rowDocente['nombre'] . ' ' . $rowDocente['apellido']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>


            <td>
                <select name="aula" required>
                    <option value="">-- Selecciona un Aula --</option>
                    <?php foreach ($aulas as $rowAula): ?>
                        <option value="<?php echo $rowAula['id_aula']; ?>">
                            <?php echo htmlspecialchars($rowAula['nombre_aula']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>


            <td>
                <select name="turno" required>
                    <option value="">-- Selecciona un Turno --</option>
                    <option value="Mañana">Mañana</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noche">Noche</option>
                </select>
            </td>


            <td>
                <input type="date" name="fecha_asignacion" required>
            </td>


            <td>
                <button type="submit" name="asignar">Asignar</button>
            </td>
        </form>
    </tr>
</table>

<?php $conn->close(); ?>

</body>
</html>
