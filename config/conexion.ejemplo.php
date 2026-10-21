<?php
// Copiar este archivo a config/conexion.php y configurar las credenciales.
$servidor = getenv('DB_HOST') ?: 'localhost';
$baseDatos = getenv('DB_NAME') ?: 'inventario';
$usuario = getenv('DB_USER') ?: 'root';
$contrasena = getenv('DB_PASSWORD');
if ($contrasena === false) {
    $contrasena = '';
}

try {
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
        $usuario,
        $contrasena,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $error) {
    error_log('Error de conexión: ' . $error->getMessage());
    http_response_code(503);
    exit('No fue posible conectarse con la base de datos.');
}
