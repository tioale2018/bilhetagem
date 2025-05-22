<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();

include_once('../inc/conexao.php');

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

// Decodifica a senha criptografada
$encrypted_i      = base64_decode($_POST['i'] ?? '');

try {
    // $cpf        = $privateKey->decrypt($encrypted_cpf);
    $entrada    = htmlspecialchars($privateKey->decrypt($encrypted_i), ENT_QUOTES | ENT_HTML5, 'UTF-8');

} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

// $entrada = htmlspecialchars($entrada, ENT_QUOTES, 'UTF-8');

$sql = "update tbentrada set ativo=0, previnculo_status=0 where id_entrada=:entrada";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':entrada', $entrada, PDO::PARAM_INT);

$pre->execute();

?>