<?php
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ci'])) {
    $ci = $_POST['ci'];


    $query = "SELECT e.id_estudiante, e.nombre, e.apellido, c.nombre AS carrera
              FROM estudiantes e
              JOIN carrera c ON e.id_carrera = c.id_carrera
              WHERE e.ci = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ci);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $estudiante = $result->fetch_assoc();
       
        echo json_encode([
            'success' => true,
            'id_estudiante' => $estudiante['id_estudiante'],
            'nombre' => $estudiante['nombre'],
            'apellido' => $estudiante['apellido'],
            'carrera' => $estudiante['carrera']
        ]);
    } else {
    
        echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
    }

    $stmt->close();
    $conn->close();
} else {
 
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
