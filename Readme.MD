# README

## 1. ¿Qué hace el proyecto?

Es un sistema de inventario que permite registrar productos, guardar su cantidad y mostrar si están disponibles o sin existencia.

## 2. ¿Qué tecnologías utiliza?
El proyecto utiliza:
* PHP v 8.4.12
* HTML
* CSS
* MariaDB
* PHP con la base de datos.
* opcional Visual Studio code

## 3. ¿Qué se necesita instalar?

Para ejecutar el proyecto utilizo Laragon, ya que incluye PHP, Apache y MySQL. También utilizo Visual Studio Code para trabajar con el código.
también utilizo:

Visual Studio Code para modificar el código.
Un navegador como Google Chrome o Microsoft Edge.

Después de instalar Laragon, debo iniciar los servicios utilizando el botón iniciar.

## 4. ¿Cómo se configura y ejecuta?

coloco la carpeta del proyecto dentro de:

C:\laragon\www

y queddaría com o

C:\laragon\www\inventario

Después inicio Laragon, activo los servicios y creo o importo la base de datos con el archivo base_datos.sql.

Finalmente abro el proyecto desde el navegador con:

http://localhost/inventario

Desde ahí puedo visualizar el inventario y agregar nuevos productos.

## Configuración de conexión y pruebas

Antes de abrir la aplicación, copia config/conexion.ejemplo.php a
config/conexion.php y configura las credenciales de MariaDB. También puedes
definir DB_HOST, DB_NAME, DB_USER y DB_PASSWORD en el entorno del servidor.

Los nombres admiten de 3 a 50 caracteres. Las cantidades deben ser enteros:
de 1 a 2147483647 al registrar; al editar se permite 0 (sin existencia).

Pruebas unitarias: php tests/test_validaciones.php

Para las pruebas de integración, crea una base independiente llamada
inventariopruebas e importa tests/estructura_pruebas.sql. Configura las variables
DB_* para esa base y ejecuta php tests/test_integracion_productos.php.
La prueba utiliza una transacción que revierte sus cambios.
