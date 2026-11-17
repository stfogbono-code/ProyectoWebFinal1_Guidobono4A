<?php
    // Carga funciones, título y datos del usuario actual.
require_once 'funciones.php';
$titulo = $titulo ?? 'Elite Boots';
$actual = usuario_actual();
    // Inicia la estructura HTML de la página.
?><!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<?php // Define el título de la pestaña y carga el CSS. ?>
<title><?= escapar($titulo) ?> | Elite Boots</title><link rel="stylesheet" href="css/estilos.css"></head>
<?php // Muestra el logo de Elite Boots y la barra superior. ?>
<body><header class="barra"><div class="contenedor barra-interior"><a class="logo" href="index.php"><span class="logo-icono">E</span> ELITE <span>BOOTS</span></a>
<?php // Muestra los enlaces según si el usuario inició sesión. ?>
<nav><?php if ($actual): ?><a href="catalogo.php">Catálogo</a><?php endif; ?>
<?php // Agrega enlaces administrativos solo si el rol es admin. ?>
<?php if ($actual): ?><a href="panel.php">Mi panel</a><?php if ($actual['rol'] === 'admin'): ?><a href="admin_usuarios.php">Usuarios</a><a href="admin_botines.php">Productos</a><?php endif; ?><a class="nav-destacado" href="cerrar_sesion.php">Salir</a>
<?php else: ?><a href="login.php">Ingresar</a><a class="nav-destacado" href="registro.php">Registrarse</a><?php endif; ?></nav></div></header>
<main class="contenedor contenido">
<?php // Muestra el aviso temporal si hay alguno y luego lo borra. ?>
<?php if (!empty($_SESSION['aviso'])): ?><div class="aviso"><?= escapar($_SESSION['aviso']) ?></div><?php unset($_SESSION['aviso']); endif; ?>
