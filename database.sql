CREATE DATABASE IF NOT EXISTS tp_auditoria;
USE tp_auditoria;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    estado TINYINT(1) DEFAULT 1, 
    hash_integridad VARCHAR(255) 
);

CREATE TABLE auditoria_usuarios (
    id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
    id_registro_afectado INT NOT NULL,
    usuario_operador VARCHAR(100) NOT NULL,
    fecha_hora DATETIME NOT NULL,
    tipo_accion VARCHAR(50) NOT NULL 
);