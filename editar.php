<?php

require_once __DIR__ . '/config/conexion.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php');
    exit;
}

$consulta = $conexion->prepare(
    'SELECT id, nombre, cantidad
     FROM productos
     WHERE id = ?'
);

$consulta->execute([$id]);

$producto = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar producto</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header class="encabezado">
        <div class="contenido-encabezado">
            <p class="etiqueta">GPDS</p>
            <h1>EDITAR PRODUCTO</h1>
            <p>Modifica la información del producto</p>
        </div>
    </header>

    <main class="contenedor">

        <section class="tarjeta formulario">

            <h2>Editar producto</h2>

            <p class="descripcion">
                Modifica el nombre o la cantidad del producto.
            </p>

            <form action="actualizar.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $producto['id']; ?>"
                >

                <div class="campo">
                    <label for="nombre">
                        Nombre del producto
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?php
                        echo htmlspecialchars($producto['nombre']);
                        ?>"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="cantidad">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        id="cantidad"
                        name="cantidad"
                        value="<?php echo $producto['cantidad']; ?>"
                        required
                    >
                </div>

                <button type="submit">
                    Guardar cambios
                </button>

                <a href="index.php">
                    Cancelar
                </a>

            </form>

        </section>

    </main>

</body>
</html>