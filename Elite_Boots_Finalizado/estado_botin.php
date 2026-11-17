<?php
    // Comprueba que la acción provenga de un administrador.
require 'funciones.php';exigir_admin();validar_csrf();require 'conexion.php';
    // Recibe el ID y la acción solicitada por POST.
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
$accion=$_POST['accion']??'';
    // Verifica que el ID y la acción sean válidos.
if(!$id||!in_array($accion,['archivar','reactivar'],true)){http_response_code(400);exit('Datos inválidos.');}
    // Prepara la consulta para archivar o reactivar el producto.
$sql=$accion==='archivar'?'UPDATE botines SET archivado_en=NOW() WHERE id=? AND archivado_en IS NULL':'UPDATE botines SET archivado_en=NULL WHERE id=? AND archivado_en IS NOT NULL';
    // Ejecuta el cambio en MySQL y vuelve al panel.
$st=$pdo->prepare($sql);$st->execute([$id]);aviso($accion==='archivar'?'Producto archivado.':'Producto reactivado.');ir('admin_botines.php');
