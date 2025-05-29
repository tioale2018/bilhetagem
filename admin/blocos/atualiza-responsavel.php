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

$encrypted_nome       = base64_decode($_POST['nome'] ?? '');
$encrypted_cpf        = base64_decode($_POST['cpf'] ?? '');
$encrypted_telefone1 = base64_decode($_POST['telefone1'] ?? '');

if (isset($_POST['telefone2']) && !empty($_POST['telefone2'])) {
    $encrypted_telefone2 = base64_decode($_POST['telefone2']);
}

$encrypted_telefone2 = base64_decode($_POST['telefone2'] ?? '');
$encrypted_email      = base64_decode($_POST['email'] ?? '');
$encrypted_idresponsavel = base64_decode($_POST['idresponsavel']);

try {
    $nome      = $privateKey->decrypt($encrypted_nome);
    $cpf       = $privateKey->decrypt($encrypted_cpf);
    $telefone1 = $privateKey->decrypt($encrypted_telefone1);

    if (isset($_POST['telefone2']) && !empty($_POST['telefone2'])) {
        $encrypted_telefone2 = base64_decode($_POST['telefone2']);
    } else {
        $encrypted_telefone2 = '';
    }
    
    $telefone2 = $privateKey->decrypt($encrypted_telefone2);
    $email     = $privateKey->decrypt($encrypted_email);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);       

} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$sql_atualiza_responsavel = "update tbresponsavel set nome=:nome, cpf=:cpf, email=:email, telefone1=:telefone1, telefone2=:telefone2 where id_responsavel=:id";
$pre_atualiza_responsavel = $connPDO->prepare($sql_atualiza_responsavel);
$pre_atualiza_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':telefone2', $telefone2, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':id', $idresponsavel, PDO::PARAM_INT);
$pre_atualiza_responsavel->execute();

?>