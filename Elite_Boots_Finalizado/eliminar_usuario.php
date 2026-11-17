
<?php
    // Comprueba que quien elimina sea administrador y valida el formulario.
require 'funciones.php';
exigir_admin();
require 'conexion.php';
validar_csrf();

    // Obtiene el ID del usuario que se quiere borrar.
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    // Impide usar un ID inválido o borrar la propia cuenta.
if (!$id || $id == usuario_actual()['id']) {
    exit('Usuario inválido.');
}

    // Elimina definitivamente en MySQL el usuario de ese ID.
$st = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$st->execute([$id]);

    // Vuelve al panel de administración de usuarios.
ir('admin_usuarios.php');
