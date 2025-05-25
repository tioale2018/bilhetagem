<?php
// die(var_dump($_POST));
require '../../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;



if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

// Decodifica a senha criptografada
$encrypted_id      = base64_decode($_POST['id'] ?? '');

try {
    // $cpf        = $privateKey->decrypt($encrypted_cpf);
    $id        = $privateKey->decrypt($encrypted_id);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}



// if ($_SERVER['REQUEST_METHOD']!="POST") {
//     header('X-PHP-Response-Code: 404', true, 404);
//     http_response_code(404);
//     exit('Requisição inválida.');
// }
function limparCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    return $cpf;
}

include_once('../inc/conexao.php');

$id = limparCPF($_POST['id']);

$sql_responsavel = "select * from tbresponsavel where cpf=:cpf";
$pre_responsavel = $connPDO->prepare($sql_responsavel);
$pre_responsavel->bindParam(':cpf', $id, PDO::PARAM_INT);
$pre_responsavel->execute();

if ($pre_responsavel->rowCount()>0) {
    $dados_responsavel = $pre_responsavel->fetchAll();
    echo json_encode($dados_responsavel);
} else {
    echo "0";
}

?>