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

// function dataParaMySQL($data) {
//     $partes = explode('/', $data);
//     if (count($partes) === 3) {
//         return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
//     }
//     return null; // Retorna null se não tiver 3 partes
// }



// $nome          = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
// $cpf           = htmlspecialchars($_POST['cpf']);
// $telefone1     = htmlspecialchars($_POST['telefone1'], ENT_QUOTES, 'UTF-8');
// $telefone2     = htmlspecialchars($_POST['telefone2'], ENT_QUOTES, 'UTF-8');
// $email         = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
// $idresponsavel = htmlspecialchars($_POST['idresponsavel'], ENT_QUOTES, 'UTF-8');

$encrypted_nome       = base64_decode($_POST['nome'] ?? '');
$encrypted_cpf        = base64_decode($_POST['cpf'] ?? '');
$encrypted_telefone1 = base64_decode($_POST['telefone1'] ?? '');
$encrypted_telefone2 = base64_decode($_POST['telefone2'] ?? '');
$encrypted_email      = base64_decode($_POST['email'] ?? '');
$encrypted_idresponsavel = base64_decode($_POST['idresponsavel']);

try {
    $nome      = $privateKey->decrypt($encrypted_nome);
    $cpf       = $privateKey->decrypt($encrypted_cpf);
    $telefone1 = $privateKey->decrypt($encrypted_telefone1);
    $telefone2 = $privateKey->decrypt($encrypted_telefone2);
    $email     = $privateKey->decrypt($encrypted_email);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);       

} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}





// if ($_SERVER['REQUEST_METHOD']!="POST") {
//     header('X-PHP-Response-Code: 404', true, 404);
//     http_response_code(404);
//     exit('Requisição inválida.');
// }
// session_start();

// include('../inc/conexao.php');
// include('../inc/funcoes.php');


// var_dump($_SESSION['dadosResponsavel']);

// $nome          = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
// $cpf           = htmlspecialchars($_POST['cpf']);
// $telefone1     = htmlspecialchars($_POST['telefone1'], ENT_QUOTES, 'UTF-8');
// $telefone2     = htmlspecialchars($_POST['telefone2'], ENT_QUOTES, 'UTF-8');
// $email         = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
// $idresponsavel = htmlspecialchars($_POST['idresponsavel'], ENT_QUOTES, 'UTF-8');

$sql_atualiza_responsavel = "update tbresponsavel set nome=:nome, cpf=:cpf, email=:email, telefone1=:telefone1, telefone2=:telefone2 where id_responsavel=:id";
$pre_atualiza_responsavel = $connPDO->prepare($sql_atualiza_responsavel);
$pre_atualiza_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':telefone2', $telefone2, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':id', $idresponsavel, PDO::PARAM_INT);
$pre_atualiza_responsavel->execute();

// $_SESSION['dadosResponsavel'] = procuraResponsavel($cpf);

// echo var_dump($connPDO->errorInfo());

/*
$nome          = $_POST['nome'];
$nascimento    = $_POST['nascimento'];
$vinculo       = $_POST['vinculo'];
$pacote        = $_POST['pacote'];
$idresponsavel = $_POST['idresponsavel'];
$idprevenda    = $_POST['idprevenda'];

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

$sql_insere_entrada = "insert into tbentrada (id_prevenda, id_vinculado, previnculo_status, id_pacote) values (:id_prevenda, :id_vinculado, 1, :id_pacote)";
$pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
$pre_insere_entrada->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':id_vinculado', $ultimo_id, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':id_pacote', $pacote, PDO::PARAM_INT);
$pre_insere_entrada->execute();
*/
?>