<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/styless.css">
    <title>Error</title>
    <style>
    </style>
</head>
<body>

<?php
if (isset($_GET['mensaje'])) {
    $mensaje = urldecode($_GET['mensaje']);
} else {
    $mensaje = "Ha ocurrido un error desconocido.";
}
?>

<div class="modal">
    <h2>Error en la asignación</h2>
    <p><?php echo $mensaje; ?></p>
    <button onclick="window.history.back();">Regresar</button>
</div>

</body>
</html>
