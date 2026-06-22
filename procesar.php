<?php
$pdo = new PDO("mysql:host=localhost;dbname=tp_auditoria", "root", "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $usuario_operador = "sistema_web"; 

    $clave_secreta = 'MiClaveSecreta123';

    $estado_inicial = 1;
    $hash = hash('sha256', $nombre . $email . $estado_inicial . $clave_secreta);
   
    $consulta = $pdo->prepare("INSERT INTO usuarios (nombre, email, hash_integridad) VALUES (?, ?, ?)");
    $consulta->execute([$nombre, $email, $hash]);
    
    $id_usuario = $pdo->lastInsertId();
    
    $consulta_aud = $pdo->prepare("INSERT INTO auditoria_usuarios (id_registro_afectado, usuario_operador, fecha_hora, tipo_accion) VALUES (?, ?, NOW(), 'Inserción')");
    $consulta_aud->execute([$id_usuario, $usuario_operador]);

    echo "<h3>Registro exitoso.</h3><a href='index.html'>Volver</a>";
}
?>