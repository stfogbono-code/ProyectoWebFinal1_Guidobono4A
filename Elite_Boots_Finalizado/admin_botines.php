<?php
    // Solo administradores pueden ver esta sección.
require 'funciones.php'; exigir_admin(); require 'conexion.php';
    // Obtiene de MySQL todos los botines, activos y archivados.
$botines=$pdo->query('SELECT * FROM botines ORDER BY archivado_en IS NOT NULL, id DESC')->fetchAll();
    // Carga el encabezado de administración de productos.
$titulo='Gestionar botines';require 'encabezado.php'; ?>
<?php // Muestra el acceso para agregar botines. ?>
<section class="cabecera"><span class="etiqueta">PANEL ADMIN</span><h1>Gestión del catálogo.</h1><p></p><a class="boton" href="editar_botin.php">+ Agregar botines</a></section>
<?php // Prepara la tabla con los productos y sus estados. ?>
<div class="tabla-contenedor"><table><thead><tr><th>ID</th><th>Botines</th><th>Talle</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php // Recorre los productos y permite editar, archivar o reactivar. ?>
<?php foreach($botines as $botin): ?><tr><td><?= (int)$botin['id'] ?></td><td><?= escapar($botin['marca'].' '.$botin['modelo']) ?></td><td><?= escapar($botin['talle']) ?></td><td>$ <?= escapar(number_format((float)$botin['precio'],0,',','.')) ?></td><td><span class="estado"><?= $botin['archivado_en']?'Archivado':'Publicado' ?></span></td><td class="acciones-tabla"><?php if(!$botin['archivado_en']): ?><a href="editar_botin.php?id=<?= (int)$botin['id'] ?>">Editar</a><?php endif; ?><form method="post" action="estado_botin.php" onsubmit="return confirm('¿Confirmás esta acción?')"><input type="hidden" name="csrf" value="<?= escapar(token_csrf()) ?>"><input type="hidden" name="id" value="<?= (int)$botin['id'] ?>"><input type="hidden" name="accion" value="<?= $botin['archivado_en']?'reactivar':'archivar' ?>"><button class="<?= $botin['archivado_en']?'enlace-accion':'enlace-peligro' ?>"><?= $botin['archivado_en']?'Reactivar':'Archivar' ?></button></form></td></tr><?php endforeach; ?><?php if(!$botines): ?><tr><td colspan="6">Todavía no hay botines cargados.</td></tr><?php endif; ?></tbody></table></div><p class="nota">Archivar quita un producto del catálogo sin borrar sus datos.</p>
<?php require 'pie.php'; ?>
