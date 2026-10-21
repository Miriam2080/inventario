
<?php

require_once __DIR__ . '/../src/validaciones.php';

// PRUEBAS PARA EL NOMBRE DEL PRODUCTO
$casosNombre = [
    ["nombre válido: Teclado", "Teclado", true],
    ["nombre vacío", "", false],
    ["nombre con espacios", "  ", false],
    ["nombre válido: Mouse", "Mouse", true],
    ["nombre válido: Monitor", "Monitor", true],
    ["nombre corto", "ab", false],
    ["nombre limite", str_repeat("á", 50), true],
    ["nombre largo", str_repeat("á", 51), false]
];

// PRUEBAS PARA LA CANTIDAD DEL PRODUCTO
$casosCantidad = [
    ["cantidad válida: 10", "10", true],
    ["cantidad válida: 1", "1", true],
    ["cantidad vacía", "", false],
    ["cantidad con espacios", "  ", false],
    ["cantidad cero", "0", false],
    ["cantidad negativa", "-5", false],
    ["cantidad con letras", "abc", false],
    ["cantidad decimal", "2.5", false],
    ["cantidad máxima", "2147483647", true],
    ["cantidad fuera de rango SQL", "2147483648", false],
    ["cantidad arreglo", [], false],
    ["cantidad booleana", true, false]
];

$correctas = 0;
$fallidas = 0;


echo "\n----- PRUEBAS DE NOMBRE -----\n";

foreach ($casosNombre as [$descripcion, $entrada, $esperado]) {

    $obtenido = validarNombreProducto($entrada);

    if ($obtenido === $esperado) {
        echo "PASS - $descripcion\n";
        $correctas++;
    } else {
        echo "FAIL - $descripcion\n";
        echo " Esperado: " . var_export($esperado, true) . "\n";
        echo " Obtenido: " . var_export($obtenido, true) . "\n";
        $fallidas++;
    }
}


echo "\n----- PRUEBAS DE CANTIDAD -----\n";

foreach ($casosCantidad as [$descripcion, $entrada, $esperado]) {

    $obtenido = validarCantidadProducto($entrada);

    if ($obtenido === $esperado) {
        echo "PASS - $descripcion\n";
        $correctas++;
    } else {
        echo "FAIL - $descripcion\n";
        echo " Esperado: " . var_export($esperado, true) . "\n";
        echo " Obtenido: " . var_export($obtenido, true) . "\n";
        $fallidas++;
    }
}

echo "\n----- PRUEBAS DE IDENTIFICADOR -----\n";
$casosId = [
    ['1', true], ['1.5', false], ['1e2', false], ['0', false],
    ['-1', false], [[], false], ['4294967295', true], ['4294967296', false]
];
foreach ($casosId as [$entrada, $esperado]) {
    if (validarIdProducto($entrada) === $esperado) {
        echo "PASS - identificador: " . var_export($entrada, true) . PHP_EOL;
        $correctas++;
    } else {
        echo "FAIL - identificador: " . var_export($entrada, true) . PHP_EOL;
        $fallidas++;
    }
}
echo "\n------- RESUMEN -------\n";
echo "Total: " . ($correctas + $fallidas) . PHP_EOL;
echo "Correctas: $correctas\n";
echo "Fallidas: $fallidas\n";
exit($fallidas > 0 ? 1 : 0);
