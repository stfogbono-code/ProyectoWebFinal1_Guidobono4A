<?php
// Funciones compartidas por todas las páginas.
    // Inicia la sesión solo si todavía no está iniciada.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
    // Convierte los datos en texto seguro antes de mostrarlos en HTML.
function escapar($dato): string {
    return htmlspecialchars((string) $dato, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
    // Envía al navegador a otra página y termina la ejecución.
function ir(string $destino): void {
    header('Location: ' . $destino);
    exit;
}
    // Guarda un mensaje temporal en la sesión.
function aviso(string $mensaje): void {
    $_SESSION['aviso'] = $mensaje;
}
    // Obtiene los datos del usuario que inició sesión.
function usuario_actual(): ?array {
    return $_SESSION['usuario'] ?? null;
}
    // Exige que haya sesión y comprueba que la cuenta siga activa.
function exigir_login(): void {
    if (!usuario_actual()) {
        aviso('Primero iniciá sesión.');
        ir('login.php');
    }
    // Verificamos también en la BD para bloquear cuentas archivadas.
    require 'conexion.php';
    $consulta = $pdo->prepare('SELECT id, nombre, apellido, correo, dni, rol FROM usuarios WHERE id = ? AND archivado_en IS NULL');
    $consulta->execute([$_SESSION['usuario']['id']]);
    $datos = $consulta->fetch();
    if (!$datos) {
        $_SESSION = [];
        session_destroy();
        ir('login.php');
    }
    $_SESSION['usuario'] = $datos;
}
    // Impide que un cliente acceda a funciones de administrador.
function exigir_admin(): void {
    exigir_login();
    if (usuario_actual()['rol'] !== 'admin') {
        http_response_code(403);
        exit('Acceso denegado: esta sección es exclusiva del administrador.');
    }
}
    // Genera o reutiliza un token de seguridad del formulario.
function token_csrf(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
    // Comprueba que la solicitud POST tenga el token correcto.
function validar_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(token_csrf(), (string) ($_POST['csrf'] ?? ''))) {
        http_response_code(403);
        exit('Solicitud inválida. Volvé al formulario e intentá nuevamente.');
    }
}
    // Valida nombre, apellido, DNI y correo del formulario.
function validar_usuario_form(array $datos): array {
    $errores = [];
    foreach (['nombre','apellido','dni','correo'] as $campo) {
        if (trim((string)($datos[$campo] ?? '')) === '') $errores[] = 'Completá el campo ' . $campo . '.';
    }
    if (!filter_var($datos['correo'] ?? '', FILTER_VALIDATE_EMAIL)) $errores[] = 'El correo no es válido.';
    if (!preg_match('/^[0-9]{7,9}$/', (string)($datos['dni'] ?? ''))) $errores[] = 'El DNI debe tener entre 7 y 9 números.';
    if (mb_strlen((string)($datos['nombre'] ?? '')) > 80 || mb_strlen((string)($datos['apellido'] ?? '')) > 80) $errores[] = 'Nombre o apellido demasiado largo.';
    return $errores;
}
    // Comprueba que el DNI o el correo no estén repetidos en MySQL.
function existe_identidad(PDO $pdo, string $dni, string $correo, int $excepto = 0): bool {
    $st = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE (dni = ? OR correo = ?) AND id <> ?');
    $st->execute([$dni,$correo,$excepto]);
    return (int)$st->fetchColumn() > 0;
}
