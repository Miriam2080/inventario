<?php

require_once __DIR__ . '/config/conexion.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = $_POST['id'] ?? null;

// Validar que el id sea un número entero válido
if (!is_numeric($id) || (int)$id <= 0) {
    header('Location: index.php?estado=error_eliminar');
    exit;
}

try {
    $sql = 'DELETE FROM productos WHERE id = :id';
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header('Location: index.php?estado=eliminado');
    } else {
        // No se encontró el producto con ese id
        header('Location: index.php?estado=error_eliminar');
    }
    exit;

} catch (PDOException $e) {
    header('Location: index.php?estado=error_eliminar');
    exit;
}