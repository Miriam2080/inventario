<?php
session_start();

require_once __DIR__ . '/config/conexion.php';

// ─────────────────────────────────────────────
// 1. Obtener productos (con manejo de errores)
// ─────────────────────────────────────────────
try {
    $consulta = $conexion->query(
        'SELECT id, nombre, cantidad, fecharegistro
         FROM productos
         ORDER BY id ASC'
    );
    $productos = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error al listar productos: ' . $e->getMessage());
    $productos = [];
    $estado = 'error_bd';
}

// ─────────────────────────────────────────────
// 2. Estado, error y valores previos
// ─────────────────────────────────────────────
$estado = $_GET['estado'] ?? '';

// Errores detallados (vienen del procesar.php)
$error = $_SESSION['error'] ?? null;
$old   = $_SESSION['old']   ?? ['nombre' => '', 'cantidad' => ''];

// Limpiar para que no se repitan al recargar
unset($_SESSION['error'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTARIO</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
    <header class="encabezado">
        <div class="contenido-encabezado">
            <p class="etiqueta">GPDS</p>
            <h1>INVENTARIO</h1>
            <p>Registro y consulta de productos</p>
        </div>
    </header>

    <main class="contenedor">
        <section class="tarjeta formulario">
            <h2>Registrar producto</h2>

            <p class="descripcion">
                Escriba el nombre del producto y su cantidad.
            </p>

            <?php // ── Mensajes de éxito ── 
            ?>
            <?php if ($estado === 'guardado'): ?>
                <div class="mensaje correcto">
                    Producto registrado correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'eliminado'): ?>
                <div class="mensaje correcto">
                    Producto eliminado correctamente.
                </div>
            <?php endif; ?>

            <?php // ── Mensajes de error específicos (prioridad al detalle) ── 
            ?>
            <?php if ($error): ?>
                <div class="mensaje error">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php elseif ($estado === 'incompleto'): ?>
                <div class="mensaje error">
                    Debe completar todos los campos.
                </div>
            <?php elseif ($estado === 'nombre_corto'): ?>
                <div class="mensaje error">
                    El nombre debe tener mínimo 3 caracteres.
                </div>
            <?php elseif ($estado === 'cantidad_invalida'): ?>
                <div class="mensaje error">
                    La cantidad debe ser un número.
                </div>
            <?php elseif ($estado === 'cantidad_cero'): ?>
                <div class="mensaje error">
                    La cantidad debe ser mayor a 0.
                </div>
            <?php elseif ($estado === 'error_bd'): ?>
                <div class="mensaje error">
                    Ocurrió un error con la base de datos.
                </div>
            <?php elseif ($estado === 'error_eliminar'): ?>
                <div class="mensaje error">
                    No se pudo eliminar el producto.
                </div>
            <?php endif; ?>

            <form action="guardar.php" method="POST">
                <div class="campo">
                    <label for="nombre">Nombre del producto</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        minlength="3"
                        maxlength="100"
                        placeholder="Ejemplo: Café"
                        value="<?php echo htmlspecialchars($old['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required>
                </div>

                <div class="campo">
                    <label for="cantidad">Cantidad</label>
                    <input
                        type="number"
                        id="cantidad"
                        name="cantidad"
                        min="1"
                        step="any"
                        placeholder="Ejemplo: 10"
                        value="<?php echo htmlspecialchars($old['cantidad'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        required>
                </div>

                <button type="submit">Registrar producto</button>
            </form>
        </section>

        <section class="tarjeta listado">
            <div class="titulo-listado">
                <div>
                    <h2>Productos registrados</h2>
                    <p class="descripcion">
                        Total: <?php echo count($productos); ?>
                    </p>
                </div>
            </div>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($productos) === 0): ?>
                            <tr>
                                <td colspan="6" class="sin-registros">
                                    No hay productos registrados.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo (int) $producto['id']; ?></td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $producto['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $producto['cantidad'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php if ($producto['cantidad'] > 0): ?>
                                        <span class="estado disponible">
                                            Disponible
                                        </span>
                                    <?php else: ?>
                                        <span class="estado agotado">
                                            Sin existencia
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    $fechaRaw = $producto['fecharegistro'] ?? null;
                                    if ($fechaRaw && strtotime($fechaRaw)) {
                                        echo htmlspecialchars(
                                            date('d/m/Y H:i', strtotime($fechaRaw)),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                    } else {
                                        echo '<span class="sin-fecha">—</span>';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <div class="acciones">
                                        <!-- Botón Editar -->
                                        <a class="btn-editar"
                                            href="editar.php?id=<?php echo (int) $producto['id']; ?>">
                                            Editar
                                        </a>

                                        <!-- Botón Eliminar -->
                                        <form action="eliminar.php"
                                            method="POST"
                                            class="form-eliminar"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                                            <input type="hidden"
                                                name="id"
                                                value="<?php echo (int) $producto['id']; ?>">
                                            <button type="submit" class="btn-eliminar">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        U1. Planeación del proceso de desarrollo de software
    </footer>
</body>

</html>