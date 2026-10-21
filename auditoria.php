<?php
include("conexion.php");

$sql = "SELECT a.*, u.nombre FROM auditoria_usuarios a LEFT JOIN usuarios u ON u.id = a.id_registro_afectado ORDER BY a.id_auditoria DESC";
$resultado = $c->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auditoría</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

    <h1 class="text-center">Registro de auditoría</h1>

    <br>

    <a href="listado.php" class="btn btn-dark">Volver al listado</a>

    <br><br>

    <table id="tabla" class="table table-bordered table-hover">

        <thead>
            <tr class="table-info">
                <th>#</th>
                <th>ID usuario</th>
                <th>Nombre</th>
                <th>Operador</th>
                <th>Fecha y hora</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>

        <?php while ($fila = $resultado->fetch_assoc()) { ?>

        <tr>
            <td><?php echo $fila["id_auditoria"]; ?></td>
            <td><?php echo $fila["id_registro_afectado"]; ?></td>
            <td><?php echo htmlspecialchars($fila["nombre"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($fila["usuario_operador"]); ?></td>
            <td><?php echo $fila["fecha_hora"]; ?></td>
            <td><?php echo $fila["tipo_accion"]; ?></td>
        </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>$("#tabla").DataTable({ order: [[0, "desc"]] });</script>

</body>
</html>

<?php
$c->close();
?>