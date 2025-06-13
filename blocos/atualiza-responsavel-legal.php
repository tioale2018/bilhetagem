<?php
// die(var_dump($_POST));
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
session_start();

$idresponsavel = $_SESSION['dadosResponsavel'][0]['id_responsavel'];

$encrypted_nome      = base64_decode($_POST['nome_seguro'] ?? '');
$encrypted_telefone = base64_decode($_POST['telefone_seguro'] ?? '');
$encrypted_cpf      = base64_decode($_POST['cpf_seguro'] ?? '');

try {
    $nome     = $privateKey->decrypt($encrypted_nome);
    $telefone = $privateKey->decrypt($encrypted_telefone);
    $cpf      = $privateKey->decrypt($encrypted_cpf);
    
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$horaagora = time();

include('../inc/conexao.php');
include('../inc/funcoes.php');


$idPrevendaAtual   = $_SESSION['idPrevenda'];


$sql_secundario = "SELECT * from tbsecundario WHERE ativo=1 and idprevenda = :idPrevenda";
$pre_secundario = $connPDO->prepare($sql_secundario);
$pre_secundario->bindValue(':idPrevenda', $idPrevendaAtual, PDO::PARAM_INT);
$pre_secundario->execute();

if ($pre_secundario->rowCount() > 0) {
    // Se já existe um responsável secundário, atualiza os dados
    $dados_secundario = $pre_secundario->fetchAll(PDO::FETCH_ASSOC);
    $idSecundario = $dados_secundario[0]['idsecundario'];

    $sql_atualiza_secundario = "UPDATE tbsecundario SET nome=:nome, cpf=:cpf, telefone=:telefone, lastupdate=:lastupdate WHERE id=".$idSecundario;
    $pre_atualiza_secundario = $connPDO->prepare($sql_atualiza_secundario);
    $pre_atualiza_secundario->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre_atualiza_secundario->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $pre_atualiza_secundario->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $pre_atualiza_secundario->bindParam(':lastupdate', $horaagora, PDO::PARAM_INT);
    $pre_atualiza_secundario->execute();   
    
    //echo ok em json
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'message' => 'Responsável legal atualizado com sucesso.']);
} else {
    // Caso contrário, cria um novo responsável secundário
    $sql_insere_secundario = "INSERT INTO tbsecundario (nome, cpf, telefone, idprevenda, lastupdate) VALUES (:nome, :cpf, :telefone, :idPrevenda, :lastupdate)";
    $pre_insere_secundario = $connPDO->prepare($sql_insere_secundario);
    $pre_insere_secundario->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre_insere_secundario->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $pre_insere_secundario->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $pre_insere_secundario->bindParam(':idPrevenda', $idPrevendaAtual, PDO::PARAM_INT);
    $pre_insere_secundario->bindParam(':lastupdate', $horaagora, PDO::PARAM_INT);
    $pre_insere_secundario->execute();

    //echo ok em json
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'message' => 'Responsável legal adicionado com sucesso.']);
}


?>