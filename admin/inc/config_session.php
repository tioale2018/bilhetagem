<?php

// $inactive = 1800; // 30 minutos
$inactive = (isset($_SESSION['evento']) ? $_SESSION['evento']['tempo_tela'] : 1800);

ini_set('session.gc_maxlifetime', $inactive);

// Define a duração do cookie de sessão para 30 minutos
ini_set('session.cookie_lifetime', $inactive);
/*
// Definir parâmetros dos cookies de sessão
$cookieParams = session_get_cookie_params();
$cookieParams['httponly'] = true;
$cookieParams['secure'] = isset($_SERVER['HTTPS']);
$cookieParams['samesite'] = 'Strict';

// Configura os cookies da sessão com parâmetros individuais

session_set_cookie_params(
    $cookieParams['lifetime'],
    $cookieParams['path'] . '; SameSite=' . $cookieParams['samesite'],
    $cookieParams['domain'],
    $cookieParams['secure'],
    $cookieParams['httponly']
);
*/
session_start();
/*
session_write_close();

// Define o tempo de vida da sessão para 30 minutos
ini_set('session.gc_maxlifetime', $inactive);

// Define a duração do cookie de sessão para 30 minutos
ini_set('session.cookie_lifetime', $inactive);

// Definir parâmetros dos cookies de sessão
$cookieParams = session_get_cookie_params();
$cookieParams['httponly'] = true;
$cookieParams['secure'] = isset($_SERVER['HTTPS']);
$cookieParams['samesite'] = 'Strict';

// Configura os cookies da sessão com parâmetros individuais

session_set_cookie_params(
    $cookieParams['lifetime'],
    $cookieParams['path'] . '; SameSite=' . $cookieParams['samesite'],
    $cookieParams['domain'],
    $cookieParams['secure'],
    $cookieParams['httponly']
);

// Inicia a sessão
session_start();

// Regenera o ID da sessão para evitar fixação de sessão
if (!isset($_SESSION['regenerate'])) {
    session_regenerate_id(true);
    $_SESSION['regenerate'] = true;
}

// Verifica e armazena IP e User-Agent do usuário
if ((!isset($_SESSION['user_ip'])) || !isset($_SESSION['user_agent'])) {
    $_SESSION['user_ip']    = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

// Valida o IP e o User-Agent em cada requisição
if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: /admin/');
    exit();
}

$session_lifetime = $inactive; // Tempo total da sessão
$session_expiry = isset($_SESSION['timeout']) ? $_SESSION['timeout'] + $session_lifetime : time() + $session_lifetime;
$time_remaining = $session_expiry - time();

// Verifica se a sessão expirou
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy(); // Destroi a sessão
        header("Location: logoff.php"); // Redireciona para logout ou página inicial
        exit();
    }
}
$_SESSION['timeout'] = time(); // Atualiza o tempo de timeout
*/
?>
