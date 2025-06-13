<?php
require '../../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
require_once '../inc/funcoes.php';
include_once('../inc/conexao.php');

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

// $idresponsavel = htmlspecialchars($_POST['idresponsavel'], ENT_QUOTES, 'UTF-8');

$encrypted_nome       = base64_decode($_POST['nomesecundario'] ?? '');
$encrypted_cpf        = base64_decode($_POST['cpfsecundario'] ?? '');
$encrypted_telefone  = base64_decode($_POST['telefonesecundario'] ?? '');


try {
    $nome      = $privateKey->decrypt($encrypted_nome);
    $cpf       = $privateKey->decrypt($encrypted_cpf);
    $telefone  = $privateKey->decrypt($encrypted_telefone);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$idsecundario = $_SESSION['idsecundario'];

$sql_atualiza_secundario = "update tbsecundario set nome=:nome, cpf=:cpf, telefone=:telefone where id=:id";
$pre_atualiza_secundario = $connPDO->prepare($sql_atualiza_secundario);
$pre_atualiza_secundario->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre_atualiza_secundario->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$pre_atualiza_secundario->bindParam(':telefone', $telefone, PDO::PARAM_STR);
$pre_atualiza_secundario->bindParam(':id', $idsecundario, PDO::PARAM_INT);
$pre_atualiza_secundario->execute();

?>