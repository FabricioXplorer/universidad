<?php
include '../includes/conexion.php';

$queryEstudiantes = "
    SELECT e.id_estudiante, e.nombre, e.apellido, 
           c.id_carrera, c.nombre AS carrera
    FROM estudiantes e 
    LEFT JOIN carrera c ON e.id_carrera = c.id_carrera
    GROUP BY e.id_estudiante, c.id_carrera
    ORDER BY e.id_estudiante
";

$resultEstudiantes = $conn->query($queryEstudiantes);
if (!$resultEstudiantes) {
    die("Error en la consulta: " . $conn->error);
}

// Consulta todos los semestres
$querySemestres = "SELECT id_semestre, nombre_semestre FROM semestre";
$resultSemestres = $conn->query($querySemestres);
$semestres = $resultSemestres->fetch_all(MYSQLI_ASSOC);

// Consulta todas las aulas
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
    <title>Mostrar Estudiantes</title>
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<h1>Estudiantes</h1>

<button id="btnModal">Más Detalles</button>

<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Carrera</th>
        <th>Semestre</th>
        <th>Materia</th>
        <th>Docente</th>
        <th>Aula</th>
        <th>Turno</th>
        <th>Fecha de Asignación</th>
        <th>Acción</th>
    </tr>
    <?php while ($rowEstudiante = $resultEstudiantes->fetch_assoc()): ?>
    <tr id="estudiante_<?php echo $rowEstudiante['id_estudiante']; ?>">
        <form action="guardar_asignacion.php" method="POST">
            <td><?php echo htmlspecialchars($rowEstudiante['nombre']); ?></td>
            <td><?php echo htmlspecialchars($rowEstudiante['apellido']); ?></td>
            <td><?php echo htmlspecialchars($rowEstudiante['carrera']); ?></td>
            
            <!-- Lista de Semestres -->
            <td>
                <select name="semestre_<?php echo $rowEstudiante['id_estudiante']; ?>">
                    <option value="">-- Selecciona un Semestre --</option>
                    <?php foreach ($semestres as $rowSemestre): ?>
                        <option value="<?php echo $rowSemestre['id_semestre']; ?>">
                            <?php echo htmlspecialchars($rowSemestre['nombre_semestre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>

            <!-- Lista de Materias -->
            <td>
                <select name="materia_<?php echo $rowEstudiante['id_estudiante']; ?>">
                    <option value="">-- Selecciona una Materia --</option>
                    <?php
                    $queryMateriasPorCarrera = "
                        SELECT id_materia, nombre 
                        FROM materias 
                        WHERE id_carrera = " . $rowEstudiante['id_carrera'];
                    $resultMateriasPorCarrera = $conn->query($queryMateriasPorCarrera);

                    while ($rowMateria = $resultMateriasPorCarrera->fetch_assoc()): ?>
                        <option value="<?php echo $rowMateria['id_materia']; ?>">
                            <?php echo htmlspecialchars($rowMateria['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>

            <!-- Lista de Docentes -->
            <td>
                <select name="docente_<?php echo $rowEstudiante['id_estudiante']; ?>">
                    <option value="">-- Selecciona un Docente --</option>
                    <?php
                    $queryDocentesPorFacultad = "
                        SELECT d.id_docente, d.nombre, d.apellido, d.profesion 
                        FROM docentes d 
                        JOIN facultad f ON d.id_facultad = f.id_facultad 
                        JOIN carrera c ON f.id_facultad = c.id_facultad 
                        WHERE c.id_carrera = " . $rowEstudiante['id_carrera'];
                    
                    $resultDocentesPorFacultad = $conn->query($queryDocentesPorFacultad);
                    
                    while ($rowDocente = $resultDocentesPorFacultad->fetch_assoc()): ?>
                        <option value="<?php echo $rowDocente['id_docente']; ?>">
                            <?php echo htmlspecialchars($rowDocente['profesion'] . ' ' . $rowDocente['nombre'] . ' ' . $rowDocente['apellido']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>

            <!-- Lista de Aulas -->
            <td>
                <select name="aula_<?php echo $rowEstudiante['id_estudiante']; ?>">
                    <option value="">-- Selecciona un Aula --</option>
                    <?php foreach ($aulas as $rowAula): ?>
                        <option value="<?php echo $rowAula['id_aula']; ?>">
                            <?php echo htmlspecialchars($rowAula['nombre_aula']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>

            <!-- Lista de Turnos -->
            <td>
                <select name="turno_<?php echo $rowEstudiante['id_estudiante']; ?>">
                    <option value="">-- Selecciona un Turno --</option>
                    <option value="Mañana">Mañana</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Noche">Noche</option>
                </select>
            </td>

            <!-- Fecha de Asignación -->
            <td>
                <input type="date" name="fecha_asignacion_<?php echo $rowEstudiante['id_estudiante']; ?>" required>
            </td>

            <!-- Botón de Asignación -->
            <td>
                <button type="submit" name="asignar" value="<?php echo $rowEstudiante['id_estudiante']; ?>">Asignar</button>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Detalles de Asignación</h2>
        <div id="modal-body">
            <!-- Aquí se mostrarán los detalles de la asignación -->
            <table>
                <thead>
                    <tr>
                        <th>ID Asignación</th>
                        <th>Nombre Estudiante</th>
                        <th>Nombre Materia</th>
                        <th>Nombre Docente</th>
                        <th>Nombre Aula</th>
                        <th>Turno</th>
                        <th>Fecha de Asignación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../includes/conexion.php';

                    // Obtener datos de la tabla asignaciones
                    $query = "SELECT a.id_asignacion, 
                               e.nombre AS nombre_estudiante, 
                               m.nombre AS nombre_materia, 
                               CONCAT(d.nombre, ' ', d.apellido) AS nombre_docente, 
                               aul.nombre_aula, 
                               a.turno, 
                               a.fecha_asignacion 
                        FROM asignaciones a
                        JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
                        JOIN materias m ON a.id_materia = m.id_materia
                        JOIN docentes d ON a.id_docente = d.id_docente
                        JOIN aulas aul ON a.id_aula = aul.id_aula"; // Asegúrate de que esta consulta sea la que deseas
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        // Mostrar los datos en el modal
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id_asignacion']}</td>
                                    <td>{$row['nombre_estudiante']}</td>
                                    <td>{$row['nombre_materia']}</td>
                                    <td>{$row['nombre_docente']}</td>
                                    <td>{$row['nombre_aula']}</td>
                                    <td>{$row['turno']}</td>
                                    <td>{$row['fecha_asignacion']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay datos disponibles.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Obtener el modal
    var modal = document.getElementById("myModal");

    // Obtener el botón que abre el modal
    var btn = document.getElementById("btnModal");

    // Obtener el elemento <span> que cierra el modal
    var span = document.getElementsByClassName("close")[0];

    // Cuando el usuario hace clic en el botón, abrir el modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Cuando el usuario hace clic en <span> (x), cerrar el modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Cuando el usuario hace clic en cualquier parte fuera del modal, cerrarlo
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>


</body>
</html>
