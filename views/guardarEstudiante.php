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

        $query = "INSERT INTO estudiantes (nombre, apellido, correo, ci, id_carrera, imagen) VALUES ('$nombre', '$apellido', '$correo', '$ci', '$id_carrera', '$target_file')";
        
        if ($conn->query($query) === TRUE) {
         
            echo "<script>
                    alert('Estudiante registrado correctamente.');
                    window.location.href = 'registroEstudiante.php'; 
                  </script>";
        } else {
  
            echo "<script>
                    alert('Error: " . $conn->error . "');
                    window.location.href = 'registroEstudiantes.php'; 
                  </script>";
        }
    } else {
     
        echo "<script>
                alert('Error al subir la imagen.');
                window.location.href = 'registroEstudiantes.php'; 
              </script>";
    }

    $conn->close();
}
?>
