<?php
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_estudiante = $_POST['asignar'];

    $semestre = $_POST["semestre_$id_estudiante"];
    $materia = $_POST["materia_$id_estudiante"];
    $docente = $_POST["docente_$id_estudiante"];
    $aula = $_POST["aula_$id_estudiante"];
    $turno = $_POST["turno_$id_estudiante"];
    $fecha_asignacion = $_POST["fecha_asignacion_$id_estudiante"];


    $queryInsert = "INSERT INTO asignaciones (id_estudiante, id_semestre, id_materia, id_docente, id_aula, turno, fecha_asignacion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($queryInsert)) {
        $stmt->bind_param("iiississ", $id_estudiante, $semestre, $materia, $docente, $aula, $turno, $fecha_asignacion);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();

header("Location: mostrarDatos.php");
exit();
?>
