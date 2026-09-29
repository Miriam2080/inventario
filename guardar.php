<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$cantidad = $_POST['cantidad'] ?? '';

if ($nombre === '' || $cantidad === '') {
    header('Location: index.php?estado=incompleto');
    exit;
}

// El nombre debe tener mínimo 3 caracteres
if (strlen($nombre) < 3) {
    header('Location: index.php?estado=nombre_corto');
    exit;
}

// La cantidad debe ser un número
if (!is_numeric($cantidad)) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

// La cantidad debe ser mayor a 0
if ((int) $cantidad <= 0) {
    header('Location: index.php?estado=cantidad_cero');
    exit;
}

$sentencia = $conexion->prepare(
    'INSERT INTO productos (nombre, cantidad)
     VALUES (:nombre, :cantidad)'
);

$sentencia->execute([
    'nombre' => $nombre,
    'cantidad' => (int) $cantidad
]);

header('Location: index.php?estado=guardado');
exit;