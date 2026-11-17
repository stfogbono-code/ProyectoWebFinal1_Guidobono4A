<?php
    // Verifica administrador, token del formulario y conexión con MySQL.
require 'funciones.php';exigir_admin();validar_csrf();require 'conexion.php';
    // Recibe el ID por POST y comprueba que sea válido.
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if(!$id){http_response_code(400);exit('ID inválido.');}
    // Evita archivar la cuenta propia del administrador.
if($id===(int)usuario_actual()['id']){http_response_code(403);exit('No podés archivar tu propia cuenta.');}
    // Marca la cuenta como archivada guardando la fecha.
$st=$pdo->prepare('UPDATE usuarios SET archivado_en=NOW() WHERE id=? AND archivado_en IS NULL');
    // Ejecuta el cambio, muestra un aviso y vuelve al listado.
$st->execute([$id]);aviso('Cuenta archivada.');ir('admin_usuarios.php');
