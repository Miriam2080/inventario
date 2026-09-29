<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = $_POST['id'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$cantidad = $_POST['cantidad'] ?? '';

if ($id === '' || $nombre === '' || $cantidad === '') {
    header('Location: index.php');
    exit;
}

if (!is_numeric($id) || !is_numeric($cantidad) || $cantidad < 0) {
    header('Location: index.php');
    exit;
}

$sql = "UPDATE productos SET nombre = ?, cantidad = ? WHERE id = ?";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    $nombre,
    $cantidad,
    $id
]);

header('Location: index.php?estado=actualizado');
exit;