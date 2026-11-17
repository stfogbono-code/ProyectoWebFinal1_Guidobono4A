<?php
    // Restringe el acceso a administradores y conecta con MySQL.
require 'funciones.php';exigir_admin();require 'conexion.php';
    // Busca los usuarios que tienen fecha de archivado.
$usuarios=$pdo->query('SELECT id,nombre,apellido,dni,correo,archivado_en FROM usuarios WHERE archivado_en IS NOT NULL ORDER BY archivado_en DESC')->fetchAll();
    // Carga el encabezado de la lista de archivados.
$titulo='Usuarios archivados';require 'encabezado.php'; ?>
<?php // Muestra el título y el enlace para volver a usuarios activos. ?>
<section class="cabecera"><span class="etiqueta">PANEL ADMIN</span><h1>Usuarios archivados.</h1><p>Las cuentas se conservan y pueden reactivarse.</p><a class="boton secundario" href="admin_usuarios.php">Volver a activos</a></section>
<?php // Muestra la tabla y el botón para reactivar cuentas. ?>
<div class="tabla-contenedor"><table><thead><tr><th>ID</th><th>Nombre</th><th>DNI</th><th>Correo</th><th>Archivado</th><th>Acción</th></tr></thead><tbody><?php foreach($usuarios as $u): ?><tr><td><?= (int)$u['id'] ?></td><td><?= escapar($u['nombre'].' '.$u['apellido']) ?></td><td><?= escapar($u['dni']) ?></td><td><?= escapar($u['correo']) ?></td><td><?= escapar($u['archivado_en']) ?></td><td><form method="post" action="reactivar_usuario.php"><input type="hidden" name="csrf" value="<?= escapar(token_csrf()) ?>"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="enlace-accion">Reactivar</button></form></td></tr><?php endforeach; ?><?php if(!$usuarios): ?><tr><td colspan="6">No hay usuarios archivados.</td></tr><?php endif; ?></tbody></table></div>
<?php require 'pie.php'; ?>
