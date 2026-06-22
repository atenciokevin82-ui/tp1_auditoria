<?php
$pdo = new PDO("mysql:host=localhost;dbname=tp_auditoria", "root", "");
$clave_secreta = 'MiClaveSecreta123';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario_operador = "sistema_web";

    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $consulta->execute([$id]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nuevo_estado = 0;
        $hash_nuevo = hash('sha256', $usuario['nombre'] . $usuario['email'] . $nuevo_estado . $clave_secreta);

        $update = $pdo->prepare("UPDATE usuarios SET estado = ?, hash_integridad = ? WHERE id = ?");
        $update->execute([$nuevo_estado, $hash_nuevo, $id]);

        $auditoria = $pdo->prepare("INSERT INTO auditoria_usuarios (id_registro_afectado, usuario_operador, fecha_hora, tipo_accion) VALUES (?, ?, NOW(), 'Soft-delete')");
        $auditoria->execute([$id, $usuario_operador]);

        echo "<h3>Usuario borrado lógicamente.</h3><a href='index.html'>Volver</a>";
    }
}
?>