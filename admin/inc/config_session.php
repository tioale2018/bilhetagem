<?php
session_start(); // Inicia a sessão

$inactive = 3600;
if (isset($_SESSION['evento']['tempo_tela'])) {
    $inactive = $_SESSION['evento']['tempo_tela'];
}

// Ajuste dos tempos
ini_set('session.gc_maxlifetime', $inactive);
ini_set('session.cookie_lifetime', $inactive);

// HTTPS seguro?
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

// Cookie de sessão
session_set_cookie_params([
    'lifetime' => $inactive,
    'path' => '/',
    'domain' => 'homologadev.com.br/',
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Evita fixação de sessão
// if (!isset($_SESSION['regenerate'])) {
//     session_regenerate_id(true);
//     $_SESSION['regenerate'] = true;
// }

// IP verdadeiro do usuário via Cloudflare
$user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];

// Proteção contra roubo de sessão (IP + User-Agent)
if (!isset($_SESSION['user_ip'], $_SESSION['user_agent'])) {
    $_SESSION['user_ip'] = $user_ip;
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif (
    $_SESSION['user_ip'] !== $user_ip ||
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']
) {
    session_unset();
    session_destroy();
    header('Location: /admin/');
    exit();
}


// Timeout por inatividade
if (isset($_SESSION['timeout']) && (time() - $_SESSION['timeout'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: logoff.php");
    exit();
}
$_SESSION['timeout'] = time();
?>
