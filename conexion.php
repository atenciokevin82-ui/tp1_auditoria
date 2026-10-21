<?php
    $host = "localhost";

    $user = "root";

    $pass = "";

    $base = "tp_auditoria";

    $c = new mysqli($host, $user, $pass, $base);

    if ($c->connect_error) die("Error de conexión: " . $c->connect_error);

    $c->set_charset("utf8mb4");

    $clave_secreta = "MiClaveSecreta123";
    $usuario = "ADMIN";

?>