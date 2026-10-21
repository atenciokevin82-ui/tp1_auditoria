<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if (!empty($nombre) && !empty($email)) {

        $estado = 1;
        $hash = hash("sha256", $nombre . $email . $estado . $clave_secreta);

        $stmt = $c->prepare("INSERT INTO usuarios (nombre, email, hash_integridad) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $hash);

        if ($stmt->execute()) {

            $idRegistro = $c->insert_id;

            $auditoria = $c->prepare("INSERT INTO auditoria_usuarios (id_registro_afectado, usuario_operador, fecha_hora, tipo_accion) VALUES (?, ?, NOW(), 'Inserción')");
            $auditoria->bind_param("is", $idRegistro, $usuario);
            $auditoria->execute();
            $auditoria->close();
        }

        $stmt->close();
    }
}

$c->close();

header("Location: listado.php");
exit;

?>