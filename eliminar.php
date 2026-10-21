<?php

include("conexion.php");

$mensaje = "No se pudo eliminar el usuario.";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {

    $id = (int)$_POST["id"];

    $consulta = $c->prepare("SELECT * FROM usuarios WHERE id = ? AND estado = 1");
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $fila = $consulta->get_result()->fetch_assoc();
    $consulta->close();

    if ($fila) {

        $nuevo_estado = 0;
        $hash = hash("sha256", $fila["nombre"] . $fila["email"] . $nuevo_estado . $clave_secreta);

        $stmt = $c->prepare("UPDATE usuarios SET estado = 0, hash_integridad = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);

        if ($stmt->execute()) {

            $auditoria = $c->prepare("INSERT INTO auditoria_usuarios (id_registro_afectado, usuario_operador, fecha_hora, tipo_accion) VALUES (?, ?, NOW(), 'Soft-delete')");
            $auditoria->bind_param("is", $id, $usuario);
            $auditoria->execute();
            $auditoria->close();

            $mensaje = "Usuario eliminado correctamente.";
        }

        $stmt->close();
    }
}

$c->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eliminar usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5 text-center">

    <h3><?php echo $mensaje; ?></h3>

    <br>

    <a href="listado.php" class="btn btn-dark">Volver al listado</a>

</div>

</body>
</html>