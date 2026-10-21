<?php

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/validaciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';

if (!validarIdProducto($id) || !is_string($nombre)) {
    header('Location: index.php?estado=error_actualizar');
    exit;
}
$nombre = trim($nombre);
if (!validarNombreProducto($nombre)) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}
// Al editar se permite cero para marcar el producto sin existencia.
if (!(is_string($cantidad) || is_int($cantidad))
    || filter_var($cantidad, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => 2147483647]
    ]) === false) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

try {
    $consulta = $conexion->prepare('SELECT id FROM productos WHERE id = ?');
    $consulta->execute([(int) $id]);
    if (!$consulta->fetchColumn()) {
        header('Location: index.php?estado=error_actualizar');
        exit;
    }
    $consulta = $conexion->prepare(
        'UPDATE productos SET nombre = ?, cantidad = ? WHERE id = ?'
    );
    $consulta->execute([$nombre, (int) $cantidad, (int) $id]);
} catch (PDOException $error) {
    error_log('Error al actualizar producto: ' . $error->getMessage());
    header('Location: index.php?estado=error_bd');
    exit;
}

header('Location: index.php?estado=actualizado');
exit;
