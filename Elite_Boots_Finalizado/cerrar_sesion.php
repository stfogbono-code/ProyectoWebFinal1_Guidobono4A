<?php
    // Carga las funciones para manejar la sesión.
require 'funciones.php';
    // Vacía los datos guardados en la sesión.
$_SESSION=[];
    // Si se usa una cookie de sesión, la elimina.
if (ini_get('session.use_cookies')) {
    $p=session_get_cookie_params();
    setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);
}
    // Termina la sesión y vuelve al login.
session_destroy();
ir('login.php');
