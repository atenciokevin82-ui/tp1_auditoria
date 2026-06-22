# tp1_auditoria

el objetivo es registrar los movimientos de una tabla (trazabilidad) y detectar si los datos fueron modificados por fuera del sistema (integridad).

## Características principales
* Auditoría: Cada inserción, modificación (Update) o borrado lógico (Soft-delete) guarda automáticamente qué usuario lo hizo, el día, la hora y la acción en la tabla `auditoria_usuarios`.
* Integridad: El script `verificar.php` recalcula un HASH SHA-256 en tiempo real usando los campos `nombre`, `email`, `estado` y una clave secreta propia del servidor. Si alguien edita la base de datos a mano, el sistema lo detecta y levanta una alerta.

## Estructura de la base de datos
en el archivo `database.sql` esta el codigo sql de la base de datos¿
