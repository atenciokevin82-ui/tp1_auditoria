<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)$_POST["id"];
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);

    // Traemos el estado actual para incluirlo en el hash
    $consulta = $c->prepare("SELECT estado FROM usuarios WHERE id = ?");
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $fila = $consulta->get_result()->fetch_assoc();
    $consulta->close();

    if ($fila) {

        $hash = hash("sha256", $nombre . $email . $fila["estado"] . $clave_secreta);

        $stmt = $c->prepare("UPDATE usuarios SET nombre = ?, email = ?, hash_integridad = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nombre, $email, $hash, $id);

        if ($stmt->execute()) {

            $auditoria = $c->prepare("INSERT INTO auditoria_usuarios (id_registro_afectado, usuario_operador, fecha_hora, tipo_accion) VALUES (?, ?, NOW(), 'Update')");
            $auditoria->bind_param("is", $id, $usuario);
            $auditoria->execute();
            $auditoria->close();
        }

        $stmt->close();
    }

    $c->close();
    header("Location: listado.php");
    exit;

} else {

    $id = (int)($_GET["id"] ?? 0);

    $consulta = $c->prepare("SELECT * FROM usuarios WHERE id = ? AND estado = 1");
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $fila = $consulta->get_result()->fetch_assoc();
    $consulta->close();

    if (!$fila) {
        header("Location: listado.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Dots&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="card p-4">

        <h3 class="mb-4">Editar Usuario</h3>

        <form action="editar.php" method="post">

            <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">

            <div class="mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($fila["nombre"]); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($fila["email"]); ?>" required>
            </div>

            <button type="submit" class="btn btn-dark w-100 mb-2">Guardar cambios</button>
            <a href="listado.php" class="btn btn-outline-dark w-100">Cancelar</a>

        </form>

    </div>

</body>

</html>

<?php

}

$c->close();

?>