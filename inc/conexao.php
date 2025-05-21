<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Frame-Options: DENY"); // Protege contra clickjacking
// header("Content-Security-Policy: frame-ancestors 'self';"); // Restringe o uso de iframes
header("X-XSS-Protection: 1; mode=block"); // Protege contra XSS
header("X-Content-Type-Options: nosniff"); // Previne MIME sniffing
// header("Referrer-Policy: no-referrer"); // Controla o envio de informações de referência

// Protege contra ataques de XSS refletido (em navegadores modernos)
// header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none';");

// Apaga o header que expõe o servidor (como "X-Powered-By: PHP/8.3.0")
header_remove("X-Powered-By");

header("Content-Security-Policy: 
    default-src 'self'; 
    script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; 
    style-src 'self' https://fonts.googleapis.com https://cdn.jsdelivr.net; 
    font-src 'self' https://fonts.gstatic.com; 
    object-src 'none';
    img-src 'self' data:;");


ini_set('session.cookie_secure', 1);      // Só HTTPS
ini_set('session.cookie_httponly', 1);    // Bloqueia acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Restringe envio cross-site

session_start();

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
  exit("Connection failed");
}

?>