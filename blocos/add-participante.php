<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');


if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

include('../inc/conexao.php');
include('../inc/funcoes.php');

//onde se lê "pacote" entenda perfil
// $nome          = $_POST['nome'];
// $nascimento    = convertDateToYMD($_POST['nascimento']);
$vinculo       = $_POST['vinculo'];
$perfil        = $_POST['pacote'];
// $idresponsavel = $_POST['idresponsavel'];
// $idprevenda    = $_POST['idprevenda'];

$encrypted_nome      = base64_decode($_POST['nome_seguro'] ?? '');
$encrypted_nascimento = base64_decode($_POST['nascimento_seguro'] ?? '');
$encrypted_idresponsavel = base64_decode($_POST['idresponsavel_seguro'] ?? '');
$encrypted_idprevenda    = base64_decode($_POST['idprevenda_seguro'] ?? '');

try {
    $nome  = $privateKey->decrypt($encrypted_nome);
    $nascimento = $privateKey->decrypt($encrypted_nascimento);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);
    $idprevenda = $privateKey->decrypt($encrypted_idprevenda);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$lembrar   = (isset($_POST['lembrarme'])?1:0);

//insere o vínculo
$sql_insere_vinculo = "insert into tbvinculados (id_responsavel, nome, nascimento, tipo, lembrar) values (:id_responsavel, :nome, :nascimento, :tipo, :lembrar)";
$pre_insere_vinculo = $connPDO->prepare($sql_insere_vinculo);
$pre_insere_vinculo->bindParam(':id_responsavel', $idresponsavel, PDO::PARAM_INT);
$pre_insere_vinculo->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre_insere_vinculo->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
$pre_insere_vinculo->bindParam(':tipo', $vinculo, PDO::PARAM_INT);
$pre_insere_vinculo->bindParam(':lembrar', $lembrar, PDO::PARAM_INT);
$pre_insere_vinculo->execute();

$ultimo_id = $connPDO->lastInsertId();

$sql_insere_entrada = "insert into tbentrada (id_prevenda, id_vinculado, perfil_acesso) values (:id_prevenda, :id_vinculado, :perfil_acesso)";
$pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
$pre_insere_entrada->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':id_vinculado', $ultimo_id, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':perfil_acesso', $perfil, PDO::PARAM_INT);

$pre_insere_entrada->execute();

?>