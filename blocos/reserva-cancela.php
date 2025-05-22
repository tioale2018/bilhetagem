<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
include_once('../inc/conexao.php');

session_start();

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

$encrypted_i      = base64_decode($_POST['i'] ?? '');
// $idprevenda = intval($_POST['i']);

try {
    $id = $privateKey->decrypt($encrypted_i);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$sql = "update tbprevenda set prevenda_status=0 where id_prevenda=:id";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':id', $id, PDO::PARAM_INT);
$pre->execute();

session_destroy();

?>