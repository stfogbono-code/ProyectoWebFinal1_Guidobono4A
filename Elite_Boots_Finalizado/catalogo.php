<?php
    // Exige una sesión iniciada y conecta con MySQL.
require 'funciones.php'; exigir_login(); require 'conexion.php';
    // Obtiene el texto buscado por marca o modelo mediante GET.
$buscar=trim((string)($_GET['buscar'] ?? ''));
    // Consulta los botines activos que coincidan con la búsqueda.
$st=$pdo->prepare('SELECT id,marca,modelo,talle,precio,descripcion,imagen FROM botines WHERE archivado_en IS NULL AND (marca LIKE ? OR modelo LIKE ?) ORDER BY id DESC');
    // Ejecuta la consulta y obtiene los productos encontrados.
$st->execute(['%'.$buscar.'%','%'.$buscar.'%']); $botines=$st->fetchAll();
    // Carga el título y el encabezado del catálogo.
$titulo='Catálogo'; require 'encabezado.php'; ?>
<section class="cabecera"><span class="etiqueta">CATÁLOGO ELITE BOOTS</span><h1>Botines disponibles.</h1><p>Descubrí los modelos publicados en nuestra tienda.</p></section>
<?php // Formulario para buscar botines sin modificar la base de datos. ?>
<form class="buscador" method="get"><input type="search" name="buscar" placeholder="Buscar marca o modelo" value="<?= escapar($buscar) ?>"><button class="boton">Buscar</button></form>
<?php // Si no hay resultados muestra un aviso; si hay, muestra las tarjetas. ?>
<?php if(!$botines): ?><div class="panel">No se encontraron botines.</div><?php else: ?>
<div class="tarjetas autos">
<?php // Recorre los botines para dibujar una tarjeta por producto. ?>
<?php foreach($botines as $botin): ?>
<article class="tarjeta auto">
<div class="auto-imagen">
<?php // Muestra la foto del producto si existe; si no, un texto alternativo. ?>
<?php if(!empty($botin['imagen'])): ?>
<img class="foto-botin" src="<?= escapar($botin['imagen']) ?>" alt="<?= escapar($botin['marca'].' '.$botin['modelo']) ?>">
<?php else: ?><span><?= escapar(strtoupper($botin['marca'])) ?></span><div class="auto-icono">EB</div><span>IMAGEN PRÓXIMAMENTE</span><?php endif; ?>
</div>
<?php // Muestra el talle, nombre, descripción y precio del botín. ?>
<div class="auto-info"><span class="etiqueta">TALLE <?= escapar((string)$botin['talle']) ?></span><h2><?= escapar($botin['marca'].' '.$botin['modelo']) ?></h2><p><?= escapar($botin['descripcion']) ?></p><strong>$ <?= escapar(number_format((float)$botin['precio'],0,',','.')) ?></strong></div>
</article>
<?php endforeach; ?></div><?php endif; ?>
<?php require 'pie.php'; ?>
