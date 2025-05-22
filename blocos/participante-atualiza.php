<?php


require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');


function dataParaMySQL($data) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return null; // Retorna null se não tiver 3 partes
}

$encrypted_nome_b64 = $_POST['nome'] ?? '';
$encrypted_nascimento_b64 = $_POST['nascimento'] ?? '';
$encrypted_idresponsavel_b64 = $_POST['idresponsavel'] ?? '';
$encrypted_cpf_b64 = $_POST['cpf'] ?? '';
$encrypted_idprevenda_b64 = $_POST['idprevenda'] ?? '';
$encrypted_idvinculado_b64 = $_POST['idvinculado'] ?? '';
$encrypted_identrada_b64 = $_POST['identrada'] ?? '';


$encrypted_nome_raw = base64_decode($encrypted_nome_b64, true);
$encrypted_nascimento_raw = base64_decode($encrypted_nascimento_b64, true);
// $encrypted_vinculo_raw = base64_decode($encrypted_vinculo_b64, true);
// $encrypted_pacote_raw = base64_decode($encrypted_pacote_b64, true);
$encrypted_idresponsavel_raw = base64_decode($encrypted_idresponsavel_b64, true);
$encrypted_idprevenda_raw = base64_decode($encrypted_idprevenda_b64, true);
$encrypted_idvinculado_raw = base64_decode($encrypted_idvinculado_b64, true);
$encrypted_identrada_raw = base64_decode($encrypted_identrada_b64, true);


try {
    $nome = $privateKey->decrypt($encrypted_nome_raw);
    $nascimento = $privateKey->decrypt($encrypted_nascimento_raw);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel_raw);
    $idprevenda = $privateKey->decrypt($encrypted_idprevenda_raw);
    $idvinculado = $privateKey->decrypt($encrypted_idvinculado_raw);
    $identrada = $privateKey->decrypt($encrypted_identrada_raw);
} catch (Exception $e) {
    die("Erro ao descriptografar: " . $e->getMessage());
}

$nascimento = dataParaMySQL($nascimento);

$lembrar       = (isset($_POST['melembrar'])?1:0);
$vinculo = $_POST['vinculo'] ?? '';
$pacote  = $_POST['pacote'] ?? '';

if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$idresponsavelSessao = $_SESSION['dadosResponsavel'][0]['id_responsavel'];
$idresponsavel = $idresponsavelSessao;

//verificar se o idvinculado pertence ao idresponsavel
$sql = "select * from tbvinculados where id_vinculado=:idvinculado and id_responsavel=:idresponsavel";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idvinculado', $idvinculado, PDO::PARAM_INT);
$pre->bindParam(':idresponsavel', $idresponsavel, PDO::PARAM_INT);
$pre->execute();
if ($pre->rowCount() > 0) {
    
    // $sql1 = "update tbvinculados set  nome=:nome, nascimento=:nascimento, tipo=:tipo, lembrar=:lembrar where id_vinculado=:idvinculado";
    $sql1 = "update tbvinculados set  nome=:nome, nascimento=:nascimento, tipo=:tipo, lembrar=:lembrar where id_vinculado=:idvinculado and id_responsavel=:idresponsavelsessao";

    $pre1 = $connPDO->prepare($sql1);
    $pre1->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre1->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
    $pre1->bindParam(':tipo', $vinculo, PDO::PARAM_STR);
    $pre1->bindParam(':lembrar', $lembrar, PDO::PARAM_INT);
    $pre1->bindParam(':idvinculado', $idvinculado, PDO::PARAM_INT);
    $pre1->bindParam(':idresponsavelsessao', $idresponsavelSessao, PDO::PARAM_INT);

    $pre1->execute();

    $sql2 = "update tbentrada set perfil_acesso=:pacote, autoriza=0 where id_entrada=:identrada";

    $pre2 = $connPDO->prepare($sql2);
    $pre2->bindParam(':pacote', $pacote, PDO::PARAM_INT);
    $pre2->bindParam(':identrada', $identrada, PDO::PARAM_INT);

    $pre2->execute();
}

?>
