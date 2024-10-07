<?php
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $id_estudiante = $_POST['id_estudiante'];
    $id_materia = $_POST['id_materia'];
    $estado = $_POST['estado'];
    $fecha = $_POST['fecha'];

    // Manejar el archivo de imagen
    $imagen = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $directorio = 'uploads/'; // Cambiar a la carpeta donde se guardarán las imágenes
    $ruta_imagen = $directorio . basename($imagen);

    // Mover la imagen a la carpeta de destino
    if (move_uploaded_file($imagen_tmp, $ruta_imagen)) {
        // Inserción en la base de datos
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
