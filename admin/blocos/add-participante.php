<?php
require '../../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;


if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

require_once '../inc/config_session.php';
require_once '../inc/functions.php';
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
$encrypted_idResponsavel = base64_decode($_POST['idresponsavel'] ?? '');
$encrypted_idprevenda    = base64_decode($_POST['idprevenda'] ?? '');
// $encrypted_lembrarme     = base64_decode($_POST['lembrarme'] ?? '');

try {
    $nome      = $privateKey->decrypt($encrypted_nome);
    $nascimento = $privateKey->decrypt($encrypted_nascimento);
    $vinculo   = $privateKey->decrypt($encrypted_vinculo);
    $perfil    = $privateKey->decrypt($encrypted_perfil);
    $idresponsavel = $privateKey->decrypt($encrypted_idResponsavel);
    $idprevenda    = $privateKey->decrypt($encrypted_idprevenda);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$lembrarme     = isset($_POST['lembrarme']) ? 1 : 0;
$nascimento = dataParaMySQL($nascimento);

// die('nascimento: ' . $nascimento);

//insere o vínculo
$sql_insere_vinculo = "insert into tbvinculados (id_responsavel, nome, nascimento, tipo, lembrar) values (:id_responsavel, :nome, :nascimento, :tipo, $lembrarme)";
$pre_insere_vinculo = $connPDO->prepare($sql_insere_vinculo);

$pre_insere_vinculo->bindParam(':id_responsavel', $idresponsavel, PDO::PARAM_INT);
$pre_insere_vinculo->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre_insere_vinculo->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
$pre_insere_vinculo->bindParam(':tipo', $vinculo, PDO::PARAM_INT);
$pre_insere_vinculo->execute();

$ultimo_id = $connPDO->lastInsertId();


// $sql_insere_entrada = "insert into tbentrada (id_prevenda, id_vinculado, previnculo_status, id_pacote, perfil_acesso) values (:id_prevenda, :id_vinculado, 1, :id_pacote, :perfil_acesso)";
$sql_insere_entrada = "insert into tbentrada (id_prevenda, id_vinculado, previnculo_status, perfil_acesso) values (:id_prevenda, :id_vinculado, 1, :perfil_acesso)";

$pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
$pre_insere_entrada->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':id_vinculado', $ultimo_id, PDO::PARAM_INT);
// $pre_insere_entrada->bindParam(':id_pacote', $pacote, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':perfil_acesso', $perfil, PDO::PARAM_INT);
$pre_insere_entrada->execute();

?>