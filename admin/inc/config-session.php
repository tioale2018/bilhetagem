<?php
session_start();

// Define tempo de inatividade (30 min por padrão)
$inactive = 1800;
if (isset($_SESSION['evento']) && is_array($_SESSION['evento']) && isset($_SESSION['evento']['tempo_tela'])) {
    $inactive = $_SESSION['evento']['tempo_tela'];
}

// Define parâmetros da sessão
ini_set('session.gc_maxlifetime', $inactive);
ini_set('session.cookie_lifetime', $inactive);

// Obtém parâmetros atuais dos cookies
$cookieParams = session_get_cookie_params();

// Define segurança dos cookies
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path' => $cookieParams['path'],
    'domain' => $cookieParams['domain'],
    'secure' => !empty($_SERVER['HTTPS']), // Apenas HTTPS se disponível
    'httponly' => true, // Impede acesso via JS
    'samesite' => 'Strict' // Proteção contra CSRF
]);

// Regenera o ID da sessão para evitar fixação de sessão
if (!isset($_SESSION['regenerate'])) {
    session_regenerate_id(true);
    $_SESSION['regenerate'] = true;
}

// Proteção contra roubo de sessão
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
if (isset($_SESSION['timeout'])) {
    if (time() - $_SESSION['timeout'] > $inactive) {
        session_unset();
        session_destroy();
        header("Location: logoff.php");
        exit();
    }
}
$_SESSION['timeout'] = time();

?>
