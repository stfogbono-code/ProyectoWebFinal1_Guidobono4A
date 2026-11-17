<?php
    // Permite entrar solo a administradores y abre la conexión MySQL.
require 'funciones.php';exigir_admin();require 'conexion.php';
    // Recibe la búsqueda desde la URL mediante GET.
$buscar=trim((string)($_GET['buscar'] ?? ''));
    // Consulta usuarios activos por nombre, apellido, DNI o correo.
$st=$pdo->prepare('SELECT id,nombre,apellido,dni,correo,rol,creado_en FROM usuarios WHERE archivado_en IS NULL AND (nombre LIKE ? OR apellido LIKE ? OR dni LIKE ? OR correo LIKE ?) ORDER BY id DESC');
    // Ejecuta la búsqueda y guarda todos los resultados.
$st->execute(array_fill(0,4,'%'.$buscar.'%'));$usuarios=$st->fetchAll();
    // Carga el encabezado de la sección de administración.
$titulo='Administrar usuarios';require 'encabezado.php'; ?>
<?php // Muestra los accesos para crear usuarios y ver los archivados. ?>
<section class="cabecera"><span class="etiqueta">PANEL ADMIN</span><h1>Gestión de usuarios.</h1><p></p><div class="acciones"><a class="boton" href="nuevo_usuario.php">+ Nuevo usuario</a><a class="boton secundario" href="usuarios_archivados.php">Ver archivados</a></div></section>
<?php // Muestra el formulario de búsqueda con el método GET. ?>
<form class="buscador" method="get"><input type="search" name="buscar" value="<?= escapar($buscar) ?>" placeholder="Buscar por nombre, apellido, DNI o correo"><button class="boton">Buscar</button></form>
<?php // Prepara la tabla que mostrará los usuarios. ?>
<div class="tabla-contenedor"><table><thead><tr><th>ID</th><th>Nombre</th><th>DNI</th><th>Correo</th><th>Rol</th><th>Acciones</th></tr></thead><tbody>
<?php // Recorre los usuarios y muestra sus datos y las acciones permitidas. ?>
<?php foreach($usuarios as $u): ?><tr><td><?= escapar($u['id']) ?></td><td><?= escapar($u['nombre'].' '.$u['apellido']) ?></td><td><?= escapar($u['dni']) ?></td><td><?= escapar($u['correo']) ?></td><td><span class="estado"><?= escapar($u['rol']) ?></span></td><td class="acciones-tabla"><a href="editar_usuario.php?id=<?= (int)$u['id'] ?>">Editar</a><?php if((int)$u['id']!==(int)usuario_actual()['id']): ?><form method="post" action="archivar_usuario.php" onsubmit="return confirm('¿Archivar esta cuenta?')"><input type="hidden" name="csrf" value="<?= escapar(token_csrf()) ?>"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button class="enlace-peligro">Archivar</button></form> 
<?php // Este formulario envía por POST el ID para eliminar un usuario. ?>
<form method="post" action="eliminar_usuario.php">
    <input type="hidden" name="csrf" value="<?= escapar(token_csrf()) ?>">
    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
    <button class="enlace-peligro">Eliminar</button>
</form>
<?php // Termina las acciones y el recorrido de los usuarios. ?>
<?php endif; ?></td></tr><?php endforeach; ?>
<?php // Si la búsqueda no devolvió usuarios, muestra un mensaje. ?>
<?php if(!$usuarios): ?><tr><td colspan="6">No se encontraron usuarios activos.</td></tr><?php endif; ?></tbody></table></div>
<?php require 'pie.php'; ?>
