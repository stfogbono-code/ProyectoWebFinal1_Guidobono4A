<?php
    // Carga las funciones que comprueban la sesión.
require 'funciones.php';
// La tienda requiere iniciar sesión antes de entrar.
    // Entra al panel si ya hay sesión, o al login si no la hay.
ir(usuario_actual() ? 'panel.php' : 'login.php');
