<?php
include("conexion.php");

$sql = "SELECT * FROM usuarios ORDER BY id";
$resultado = $c->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios registrados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid mt-4">

    <div class="row align-items-center mb-3">
        <div class="col text-start">
            <h2>Usuarios registrados</h2>
        </div>
        <div class="col text-end">
            <a class="btn btn-outline-dark" href="auditoria.php">Ver auditoría</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <table class="table table-bordered table-hover table-striped" id="tablaUSU">
            <thead>
                <tr class="table-info">
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Integridad</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

            <?php

            while ($fila = $resultado->fetch_assoc()) {

                $hash = hash("sha256", $fila["nombre"] . $fila["email"] . $fila["estado"] . $clave_secreta);

                if ($hash === $fila["hash_integridad"]) {
                    $integridad = "<span class='badge bg-success'>Correcta</span>";
                } else {
                    $integridad = "<span class='badge bg-danger'>Alterada</span>";
                }

                if ($fila["estado"] == 1) {
                    $estado = "Activo";
                } else {
                    $estado = "Eliminado";
                }

            ?>

            <tr>

                <td><?php echo $fila["id"]; ?></td>
                <td><?php echo htmlspecialchars($fila["nombre"]); ?></td>
                <td><?php echo htmlspecialchars($fila["email"]); ?></td>
                <td><?php echo $estado; ?></td>
                <td><?php echo $integridad; ?></td>

                <td>

                    <?php if ($fila["estado"] == 1) { ?>

                    <a href="editar.php?id=<?php echo $fila["id"]; ?>" class="btn btn-warning btn-sm">Editar</a>

                    <button type="button" class="btn btn-danger btn-sm btn-borrar"
                        data-id="<?php echo $fila["id"]; ?>"
                        data-nombre="<?php echo htmlspecialchars($fila["nombre"]); ?>">
                        Eliminar
                    </button>

                    <?php } ?>

                </td>

            </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>

    <div class="row mt-3 mb-4">
        <div class="col text-center">
            <a href="index.html" class="btn btn-dark">Nuevo usuario</a>
        </div>
    </div>

</div>

<!-- Modal borrado -->
<div class="modal fade" id="modalBorrado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="eliminar.php" method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Confirmacion de borrado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="borradoId">
                    Seguro desea borrar a <strong id="borradoNombre"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Borrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>

    $("#tablaUSU").DataTable({
        order: [[0, "asc"]],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json" }
    });

    var modalBorrado = new bootstrap.Modal(document.getElementById("modalBorrado"));

    // Eventos sobre la tabla porque DataTables pagina las filas
    $("#tablaUSU").on("click", ".btn-borrar", function () {
        $("#borradoId").val($(this).data("id"));
        $("#borradoNombre").text($(this).data("nombre"));
        modalBorrado.show();
    });

</script>

</body>
</html>

<?php
$c->close();
?>