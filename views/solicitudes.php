<?php
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_estudiante = $_POST['id_estudiante'];
    $id_materia = $_POST['id_materia'];
    $estado = $_POST['estado'];
    $fecha = $_POST['fecha'];

    $imagen = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $directorio = 'uploads/';
    $ruta_imagen = $directorio . basename($imagen);


    $queryValidacion = "
        SELECT * 
        FROM solicitudes 
        WHERE id_estudiante = ? 
          AND id_materia = ? 
          AND MONTH(fecha_solicitud) = MONTH(?) 
          AND YEAR(fecha_solicitud) = YEAR(?)";
    $stmtValidacion = $conn->prepare($queryValidacion);
    $stmtValidacion->bind_param("iiss", $id_estudiante, $id_materia, $fecha, $fecha);
    $stmtValidacion->execute();
    $result = $stmtValidacion->get_result();

    if ($result->num_rows > 0) {

        echo "<script>
                alert('Ya has registrado una solicitud para esta materia este mes.');
                window.location.href = 'index.php'; 
              </script>";
    } else {

        if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
            $query = "INSERT INTO solicitudes (id_estudiante, id_materia, imagen, estado, fecha_solicitud) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iisss", $id_estudiante, $id_materia, $imagen, $estado, $fecha);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Solicitud registrada correctamente.');
                        window.location.href = 'index.php'; // Cambia 'solicitar_materia.html' por la URL de tu página
                      </script>";
            } else {
                echo "<script>
                        alert('Error al registrar la solicitud.');
                        window.location.href = 'index.php'; // Cambia 'solicitar_materia.html' por la URL de tu página
                      </script>";
            }
            $stmt->close();
        } else {
            echo "<script>
                    alert('Error al subir la imagen.');
                    window.location.href = 'index.php'; // Cambia 'solicitar_materia.html' por la URL de tu página
                  </script>";
        }
    }

    $stmtValidacion->close();
    $conn->close();
}
?>