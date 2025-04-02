<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Inicia a sessão
//session_start();

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Frame-Options: DENY"); // Protege contra clickjacking
header("Content-Security-Policy: frame-ancestors 'self';"); // Restringe o uso de iframes
header("X-XSS-Protection: 1; mode=block"); // Protege contra XSS
header("X-Content-Type-Options: nosniff"); // Previne MIME sniffing
// header("Referrer-Policy: no-referrer"); // Controla o envio de informações de referência


// if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
//   $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//   header("Location: $url", true, 301);
//   exit();
// }

 $servername = "localhost";
 $server_name = $_SERVER['SERVER_NAME'];

 $username = "root";
$password = "";
$database = "bdbilhetagem";
/*
 if ($server_name == 'localhost' || $server_name == '127.0.0.1' || $server_name == '192.168.2.16' || $server_name == 'host.bilhetagem') {
  # code...
  

} else
*/
if ($server_name == 'homologadev.com.br') {
  //homologa homologadev
  # code...
  $username = "homologacom_userbilhetagem";
  $password = ")NaQ]pd-@PfQ";
  $database = "homologacom_bdbilhetagem";
} elseif ($server_name == 'rapidticket.com.br') {
  //produção rapidticket
  $username = "rapidcom_userbilhetagem";
  $password = "i#U9ZPP-IB3V";
  $database = "rapidcom_bdbilhetagem";
}

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese', 'pt_BR.utf8');

try {
  $connPDO = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  throw $e;
}

//rever quando incluir todos os eventos
//$evento = 1;

//ativa o debug em tela para usuários específcos
if ( isset($_SESSION['user_debug']) && $_SESSION['user_debug'] == 1 ) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

?>