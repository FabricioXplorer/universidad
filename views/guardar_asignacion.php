<?php
include '../includes/conexion.php';

ob_start(); 

$mensaje = '';
$errorMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idEstudiante = $_POST['asignar'];
    $idMateria = $_POST["materia_$idEstudiante"];
    $idDocente = $_POST["docente_$idEstudiante"];
    $idAula = $_POST["aula_$idEstudiante"];
    $turno = $_POST["turno_$idEstudiante"];
    $fechaAsignacion = $_POST["fecha_asignacion_$idEstudiante"];

    // Verificar si el docente ya tiene 3 asignaciones
    $queryContarAsignaciones = "
        SELECT COUNT(*) as total_asignaciones
        FROM asignaciones
        WHERE id_docente = ?
    ";
    
    $stmt = $conn->prepare($queryContarAsignaciones);
    $stmt->bind_param('i', $idDocente);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['total_asignaciones'] >= 3) {
        $mensaje = "El docente ya tiene 3 asignaciones y no puede ser asignado nuevamente.";
        header("Location: error.php?mensaje=" . urlencode($mensaje));
        exit; // Asegúrate de salir después de redirigir
    } else {
        // Validar si el docente ya está asignado en ese turno para este mes
        $queryValidarTurno = "
            SELECT COUNT(*) as total_turno
            FROM asignaciones
            WHERE id_docente = ? AND turno = ? AND MONTH(fecha_asignacion) = MONTH(?) AND YEAR(fecha_asignacion) = YEAR(?)
        ";

        $stmt = $conn->prepare($queryValidarTurno);
        $stmt->bind_param('isss', $idDocente, $turno, $fechaAsignacion, $fechaAsignacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $dataTurno = $result->fetch_assoc();

        if ($dataTurno['total_turno'] > 0) {
            $mensaje = "El docente ya está asignado en el turno '$turno' para este mes.";
            header("Location: error.php?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            // Validar si el docente ya está asignado a esa aula para este mes
            $queryValidarAula = "
                SELECT COUNT(*) as total_aula
                FROM asignaciones
                WHERE id_docente = ? AND id_aula = ? AND MONTH(fecha_asignacion) = MONTH(?) AND YEAR(fecha_asignacion) = YEAR(?)
            ";

            $stmt = $conn->prepare($queryValidarAula);
            $stmt->bind_param('isss', $idDocente, $idAula, $fechaAsignacion, $fechaAsignacion);
            $stmt->execute();
            $result = $stmt->get_result();
            $dataAula = $result->fetch_assoc();

            if ($dataAula['total_aula'] > 0) {
                $mensaje = "El docente ya está asignado a la aula '$idAula' para este mes.";
                header("Location: error.php?mensaje=" . urlencode($mensaje));
                exit;
            } else {
                // Realizar la inserción
                $queryInsertar = "
                    INSERT INTO asignaciones (id_estudiante, id_materia, id_docente, id_aula, turno, fecha_asignacion) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ";

                $stmt = $conn->prepare($queryInsertar);
                $stmt->bind_param('iiisss', $idEstudiante, $idMateria, $idDocente, $idAula, $turno, $fechaAsignacion);
                
                if ($stmt->execute()) {
                    header("Location: mostrarDatos.php");
                    exit; // Salir después de redirigir
                } else {
                    $mensaje = "Error en la asignación: " . $stmt->error;
                    header("Location: error.php?mensaje=" . urlencode($mensaje));
                    exit;
                }
            }
        }
    }
    $stmt->close();
}

ob_end_flush(); 
?>
