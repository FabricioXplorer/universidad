<?php
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $ci = $_POST['ci'];
    $id_carrera = $_POST['id_carrera'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        echo "El archivo ". htmlspecialchars(basename($_FILES["imagen"]["name"])). " ha sido subido.";
    } else {
        echo "Error al subir la imagen.";
    }

    $query = "INSERT INTO estudiantes (nombre, apellido, correo, ci, id_carrera, imagen) VALUES ('$nombre', '$apellido', '$correo', '$ci', '$id_carrera', '$target_file')";
    
    if ($conn->query($query) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
