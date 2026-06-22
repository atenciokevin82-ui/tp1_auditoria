<?php
$pdo = new PDO("mysql:host=localhost;dbname=tp_auditoria", "root", "");
$clave_secreta = 'MiClaveSecreta123';

if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['email'])) {
    $id = $_POST['id'];
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_email = $_POST['email'];
    $usuario_operador = "sistema_web";

    $consulta = $pdo->prepare("SELECT estado FROM usuarios WHERE id = ?");
    $consulta->execute([$id]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $estado_actual = $usuario['estado'];

        $hash_nuevo = hash('sha256', $nuevo_nombre . $nuevo_email . $estado_actual . $clave_secreta);

        $update = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, hash_integridad = ? WHERE id = ?");
        $update->execute([$nuevo_nombre, $nuevo_email, $hash_nuevo, $id]);

        $auditoria = $pdo->prepare("INSERT INTO auditoria_usuarios (id_registro_afectado, usuario_operador, fecha_hora, tipo_accion) VALUES (?, ?, NOW(), 'Update')");
        $auditoria->execute([$id, $usuario_operador]);

        echo "<h3>Usuario actualizado correctamente.</h3><a href='index.html'>Volver</a>";
    }
}
?>