<?php
include '../includes/conexion.php';

ob_start();

$mensaje = '';
$errorMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idMateria = isset($_POST["materia"]) ? $_POST["materia"] : null;
    $idDocente = isset($_POST["docente"]) ? $_POST["docente"] : null;
    $idAula = isset($_POST["aula"]) ? $_POST["aula"] : null;
    $turno = isset($_POST["turno"]) ? $_POST["turno"] : null;
    $fechaAsignacion = isset($_POST["fecha_asignacion"]) ? $_POST["fecha_asignacion"] : null;


    if ($idMateria === null || $idDocente === null || $idAula === null || $turno === null || $fechaAsignacion === null) {
        $mensaje = "Todos los campos son obligatorios.";
        header("Location: error.php?mensaje=" . urlencode($mensaje));
        exit;
    }


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
        exit;
    } else {

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

                $queryInsertar = "
                    INSERT INTO asignaciones (id_materia, id_docente, id_aula, turno, fecha_asignacion) 
                    VALUES (?, ?, ?, ?, ?)
                ";

                $stmt = $conn->prepare($queryInsertar);
                $stmt->bind_param('iiiss', $idMateria, $idDocente, $idAula, $turno, $fechaAsignacion);
                
                if ($stmt->execute()) {
                    header("Location: mostrarDatos.php");
                    exit;
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
