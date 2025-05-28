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

function dataParaMySQL($data) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return null; // Retorna null se não tiver 3 partes
}

$encrypted_nome       = base64_decode($_POST['nome'] ?? '');
$encrypted_nascimento = base64_decode($_POST['nascimento'] ?? '');
$encrypted_vinculo    = base64_decode($_POST['vinculo'] ?? '');
$encrypted_perfil     = base64_decode($_POST['perfil'] ?? '');
$enccrypted_idvinculado   = base64_decode($_POST['idvinculado'] ?? '');
$encrypted_identrada     = base64_decode($_POST['identrada'] ?? '');

try {
    $nome      = $privateKey->decrypt($encrypted_nome);
    $nascimento = $privateKey->decrypt($encrypted_nascimento);
    $vinculo   = $privateKey->decrypt($encrypted_vinculo);
    $perfil    = $privateKey->decrypt($encrypted_perfil);
    $idvinculado   = $privateKey->decrypt($enccrypted_idvinculado);
    $identrada     = $privateKey->decrypt($encrypted_identrada);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$nascimento = dataParaMySQL($nascimento);

$lembrar       = (isset($_POST['melembrar'])?1:0);

//rever a quary abaixo
$sql1 = "update tbvinculados set  nome=:nome, nascimento=:nascimento, tipo=:tipo, lembrar=:lembrar where id_vinculado=:idvinculado";

$pre1 = $connPDO->prepare($sql1);
$pre1->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre1->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
$pre1->bindParam(':tipo', $vinculo, PDO::PARAM_STR);
$pre1->bindParam(':lembrar', $lembrar, PDO::PARAM_INT);
$pre1->bindParam(':idvinculado', $idvinculado, PDO::PARAM_INT);

$pre1->execute();

// $sql2 = "update tbentrada set perfil_acesso=:perfil, id_pacote=:pacote where id_entrada=:identrada";
$sql2 = "update tbentrada set perfil_acesso=:perfil where id_entrada=:identrada";

$pre2 = $connPDO->prepare($sql2);
// $pre2->bindParam(':pacote', $pacote, PDO::PARAM_INT);
$pre2->bindParam(':perfil', $perfil, PDO::PARAM_INT);
$pre2->bindParam(':identrada', $identrada, PDO::PARAM_INT);

$pre2->execute();
?>