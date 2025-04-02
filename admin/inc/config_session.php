<?php
session_start();
// $inactive = 1800; // 30 minutos
$inactive = (isset($_SESSION['evento']) ? $_SESSION['evento']['tempo_tela'] : 1800);

// session_write_close();

// Define o tempo de vida da sessão para 30 minutos
ini_set('session.gc_maxlifetime', $inactive);

// Define a duração do cookie de sessão para 30 minutos
ini_set('session.cookie_lifetime', $inactive);

// o trecho foi melhorado no chatgpt e adicionado aqui por Alessandro Silva

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

/*

// Verifica se o protocolo é HTTPS
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

// Definir parâmetros dos cookies de sessão com valores atualizados
$cookieParams = session_get_cookie_params();
$cookieParams['httponly'] = true; // Impede acesso via JavaScript
$cookieParams['secure'] = $isSecure; // Apenas via HTTPS
$cookieParams['samesite'] = 'Strict'; // Proteção contra CSRF

// Definir o domínio da sessão (ajustado para seu ambiente)
$cookieParams['domain'] = 'homologadev.com.br'; // Ajuste de acordo com seu domínio

// Configura os cookies da sessão com os parâmetros definidos
session_set_cookie_params(
    $cookieParams['lifetime'], // Duração do cookie de sessão
    $cookieParams['path'], // Caminho onde o cookie estará disponível
    $cookieParams['domain'], // Domínio do cookie
    $cookieParams['secure'], // Definido como verdadeiro se for HTTPS
    $cookieParams['httponly'], // Impede o acesso via JavaScript
    true // Sempre define o parâmetro SameSite
);

*/



// Inicia a sessão
// session_start();

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

/*
// Verifica se a variável de sessão 'timeout' existe
if (isset($_SESSION['timeout'])) {
    // Calcula o tempo de inatividade
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy(); // Destroi a sessão
        header("Location: logoff.php"); // Redireciona para logout ou página inicial
    }
}
$_SESSION['timeout'] = time(); // Atualiza o tempo de timeout
*/

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

?>
