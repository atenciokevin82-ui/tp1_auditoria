<?php
$pdo = new PDO("mysql:host=localhost;dbname=tp_auditoria", "root", "");
$clave_secreta = 'MiClaveSecreta123'; 

$consulta = $pdo->query("SELECT * FROM usuarios");
$usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Informe de Auditoría de Integridad</h2>";

foreach ($usuarios as $fila) {
    // Agregamos $fila['estado'] a la ecuación
    $hash_actual = hash('sha256', $fila['nombre'] . $fila['email'] . $fila['estado'] . $clave_secreta);

    if ($hash_actual === $fila['hash_integridad']) {
        $situacion = ($fila['estado'] == 0) ? " (Borrado Lógico)" : " (Activo)";
        echo "<p style='color:green;'>Usuario " . $fila['nombre'] . $situacion . ": INTEGRIDAD CORRECTA.</p>";
    } else {
        echo "<p style='color:red;'><strong>¡ALERTA! Usuario " . $fila['nombre'] . ": EL REGISTRO HA SIDO ALTERADO POR FUERA DEL SISTEMA.</strong></p>";
    }
}
echo "<a href='index.html'>Volver</a>";
?>