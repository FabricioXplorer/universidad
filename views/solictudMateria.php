<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style.css">
    <title>Solicitud Materia</title>
</head>
<body>
    <h1>Solicitud de Materia</h1>

    <!-- Primer formulario: Solicitud de CI -->
    <form id="primer-formulario" action="buscarEstudiantes.php" method="POST" enctype="multipart/form-data" onsubmit="return buscarEstudiante(event)">
        <label for="ci">C.I.:</label>
        <input type="text" id="ci" name="ci" required><br>
        <button type="submit">Solicitar</button>
    </form>

    <!-- Segundo formulario: Mostrar los datos encontrados y completar la solicitud -->
    <form id="segundo-formulario" action="solicitudes.php" method="POST" enctype="multipart/form-data" style="display: none;">
        <input type="hidden" id="id_estudiante" name="id_estudiante">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" readonly><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" readonly><br>

        <label for="carrera">Carrera:</label>
        <input type="text" id="carrera" name="carrera" readonly><br>

        <label for="id_materia">Materia:</label>
        <select id="id_materia" name="id_materia" required>
            <option value="">Seleccionar Materia</option>
            <?php
            include '../includes/conexion.php'; 

            $query = "SELECT id_materia, nombre FROM materias";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id_materia'] . "'>" . $row['nombre'] . "</option>";
            }

            $conn->close();
            ?>
        </select><br>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required><br>

        <label for="imagen">Subir Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br>

        <button type="submit">Registrar</button>
    </form>

    <script>
        // Función para buscar el estudiante y mostrar el segundo formulario
        function buscarEstudiante(event) {
            event.preventDefault();

            const ci = document.getElementById('ci').value;

            // Realiza una petición AJAX a PHP para buscar el estudiante
            fetch('buscarEstudiantes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ci=${ci}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar el segundo formulario con los datos del estudiante
                    document.getElementById('id_estudiante').value = data.id_estudiante;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('apellido').value = data.apellido;
                    document.getElementById('carrera').value = data.carrera;

                    // Mostrar el segundo formulario
                    document.getElementById('segundo-formulario').style.display = 'block';
                    document.getElementById('primer-formulario').style.display = 'none';
                } else {
                    alert('No se encontró el estudiante con el C.I. proporcionado.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
