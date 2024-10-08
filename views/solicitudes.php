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


    if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {

        $query = "INSERT INTO solicitudes (id_estudiante, id_materia, imagen, estado, fecha_solicitud) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisss", $id_estudiante, $id_materia, $imagen, $estado, $fecha);

        if ($stmt->execute()) {
            echo "Solicitud registrada correctamente.";
        } else {
            echo "Error al registrar la solicitud.";
        }

        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }

    $conn->close();
}
?>
