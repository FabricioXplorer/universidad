<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style.css">
    <title>Registro de Estudiante</title>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
    <h1>Registro de Estudiante</h1>
    <form action="guardarEstudiante.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required><br>

        <label for="ci">C.I.:</label>
        <input type="text" id="ci" name="ci" required><br>

        <label for="id_carrera">Carrera:</label>
        <select id="id_carrera" name="id_carrera" required>
            <option value="">Seleccionar Carrera</option>
            <?php
            include '../includes/conexion.php'; 

            $query = "SELECT id_carrera, nombre FROM carrera";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id_carrera'] . "'>" . $row['nombre'] . "</option>";
            }

            $conn->close();
            ?>
        </select><br>

        <label for="imagen">Subir Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required><br>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
