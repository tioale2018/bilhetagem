<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

// Decodifica a senha criptografada
// $identrada = $_POST['participante'];
$encrypted_identrada     = base64_decode($_POST['participante'] ?? '');

try {
    $identrada        = $privateKey->decrypt($encrypted_identrada);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST' ) {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include('../inc/conexao.php');

// $identrada = $_POST['participante'];
$horaagora = time();

$sql_verifica_responsavel = "SELECT tbentrada.id_vinculado, tbvinculados.id_responsavel FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
WHERE tbentrada.id_entrada=:identrada";

$pre_verifica_responsavel = $connPDO->prepare($sql_verifica_responsavel);
$pre_verifica_responsavel->bindParam(':identrada', $identrada, PDO::PARAM_INT);
$pre_verifica_responsavel->execute();

$row_verifica_responsavel = $pre_verifica_responsavel->fetchAll();
if ($row_verifica_responsavel[0]['id_responsavel']==$_SESSION['dadosResponsavel'][0]['id_responsavel']) {
    $sql_atualiza_termo_participante = "update tbentrada set autoriza=1, datahora_autoriza='$horaagora' where id_entrada=:identrada";
    $pre_atualiza_termo_participante = $connPDO->prepare($sql_atualiza_termo_participante);
    $pre_atualiza_termo_participante->bindParam(':identrada', $identrada, PDO::PARAM_INT);
    $pre_atualiza_termo_participante->execute();
}



?>