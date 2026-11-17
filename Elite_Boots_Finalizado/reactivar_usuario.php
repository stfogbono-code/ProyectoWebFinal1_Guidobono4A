<?php
    // Verifica permisos y token antes de modificar datos.
require 'funciones.php';exigir_admin();validar_csrf();require 'conexion.php';
    // Recibe el ID del usuario archivado.
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if(!$id){http_response_code(400);exit('ID inválido.');}
    // Quita su fecha de archivado para volver a activarlo.
$st=$pdo->prepare('UPDATE usuarios SET archivado_en=NULL WHERE id=? AND archivado_en IS NOT NULL');
    // Guarda el cambio y vuelve a la lista de archivados.
$st->execute([$id]);aviso('Cuenta reactivada.');ir('usuarios_archivados.php');
