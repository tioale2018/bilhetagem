<?php
$inactive = 3600;
if (session_status() === PHP_SESSION_ACTIVE) {
    $inactive = $_SESSION['evento']['tempo_tela'];
}

// session_start();

// Define tempo de inatividade (30 min padrão)

// if (isset($_SESSION['evento']) && is_array($_SESSION['evento']) && isset($_SESSION['evento']['tempo_tela'])) {
//     $inactive = $_SESSION['evento']['tempo_tela'];
// }

// Define o tempo de vida da sessão e cookies
ini_set('session.gc_maxlifetime', $inactive);
ini_set('session.cookie_lifetime', $inactive);

// Verifica se a conexão é segura (HTTPS)
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

// Define parâmetros do cookie da sessão
session_set_cookie_params([
    'lifetime' => $inactive,
    'path' => '/',
    'domain' => 'homologadev.com.br', // Ajuste para seu domínio
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Inicia a sessão novamente com as novas configurações
session_start();

// Regenera o ID da sessão para evitar fixação de sessão
if (!isset($_SESSION['regenerate'])) {
    session_regenerate_id(true);
    $_SESSION['regenerate'] = true;
}

// Proteção contra roubo de sessão (IP e User-Agent)
if (!isset($_SESSION['user_ip'], $_SESSION['user_agent'])) {
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: /admin/');
    exit();
}

// Controle de expiração da sessão
if (isset($_SESSION['timeout']) && (time() - $_SESSION['timeout'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: logoff.php");
    exit();
}
$_SESSION['timeout'] = time();


$session_lifetime = $inactive; // Tempo total da sessão
$session_expiry = isset($_SESSION['timeout']) ? $_SESSION['timeout'] + $session_lifetime : time() + $session_lifetime;
$time_remaining = $session_expiry - time();

?>
