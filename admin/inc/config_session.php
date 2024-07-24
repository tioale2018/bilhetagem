<?php
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
if (!isset($_SESSION['user_ip']) || !isset($_SESSION['user_agent'])) {
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

// Valida o IP e o User-Agent em cada requisição
if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: /admin/');
    exit();
}


?>
