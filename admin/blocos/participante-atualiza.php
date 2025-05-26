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
// $encrypted_idResponsavel = base64_decode($_POST['idresponsavel'] ?? '');
$encrypted_idprevenda    = base64_decode($_POST['idprevenda'] ?? '');
// $encrypted_lembrarme     = base64_decode($_POST['lembrarme'] ?? '');

try {
    $nome      = $privateKey->decrypt($encrypted_nome);
    $nascimento = $privateKey->decrypt($encrypted_nascimento);
    $vinculo   = $privateKey->decrypt($encrypted_vinculo);
    $perfil    = $privateKey->decrypt($encrypted_perfil);
    // $idresponsavel = $privateKey->decrypt($encrypted_idResponsavel);
    $idprevenda    = $privateKey->decrypt($encrypted_idprevenda);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}





// if ($_SERVER['REQUEST_METHOD']!="POST") {
//     header('X-PHP-Response-Code: 404', true, 404);
//     http_response_code(404);
//     exit('Requisição inválida.');
// }
// session_start();
// include_once('../inc/conexao.php');
// include_once('../inc/funcoes.php');

$nome          = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
$nascimento    = convertDateToYMD(htmlspecialchars($_POST['nascimento'], ENT_QUOTES, 'UTF-8'));
$vinculo       = htmlspecialchars($_POST['vinculo'], ENT_QUOTES, 'UTF-8');
$perfil        = htmlspecialchars($_POST['perfil'], ENT_QUOTES, 'UTF-8');
// $pacote        = htmlspecialchars($_POST['pacote'], ENT_QUOTES, 'UTF-8');
$idresponsavel = htmlspecialchars($_POST['idresponsavel'], ENT_QUOTES, 'UTF-8');
$idprevenda    = htmlspecialchars($_POST['idprevenda'], ENT_QUOTES, 'UTF-8');
$idvinculado   = htmlspecialchars($_POST['idvinculado'], ENT_QUOTES, 'UTF-8');
$identrada     = htmlspecialchars($_POST['identrada'], ENT_QUOTES, 'UTF-8');

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