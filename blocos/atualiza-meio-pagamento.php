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

// $idresponsavel = $_SESSION['dadosResponsavel'][0]['id_responsavel'];

$encrypted_meioPagamento      = base64_decode($_POST['meioPagamento'] ?? '');


try {
    $meioPagamento = $privateKey->decrypt($encrypted_meioPagamento);

} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$horaagora = time();

include('../inc/conexao.php');
include('../inc/funcoes.php');


$idPrevendaAtual   = $_SESSION['idPrevenda'];


$sql_prevenda_info = "SELECT * FROM tbprevenda_info WHERE ativo=1 and idprevenda = :idPrevenda";
$pre_prevenda_info = $connPDO->prepare($sql_prevenda_info);
$pre_prevenda_info->bindValue(':idPrevenda', $idPrevendaAtual, PDO::PARAM_INT);
$pre_prevenda_info->execute();

if ($pre_prevenda_info->rowCount() > 0) {
    $row_prevendainfo = $pre_prevenda_info->fetchAll(PDO::FETCH_ASSOC);
    $id_prevendainfo = $row_prevendainfo[0]['id'];
    //se ja existir atualiza
    $sql_atualiza_prevenda = "UPDATE tbprevenda_info SET meiopgto=:meioPagamento WHERE id=:idPrevenda";
    $pre_atualiza_prevenda = $connPDO->prepare($sql_atualiza_prevenda);
    $pre_atualiza_prevenda->bindParam(':meioPagamento', $meioPagamento, PDO::PARAM_STR);
    $pre_atualiza_prevenda->bindParam(':idPrevenda', $id_prevendainfo, PDO::PARAM_INT);
    $pre_atualiza_prevenda->execute();
    //echo ok em json
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'message' => 'Atualizado com sucesso.']);

} else {
    // Se não existir, cria uma nova entrada
    $sql_insere_prevenda = "INSERT INTO tbprevenda_info (idprevenda, meiopgto) VALUES (:idPrevenda, :meioPagamento)";
    $pre_insere_prevenda = $connPDO->prepare($sql_insere_prevenda);
    $pre_insere_prevenda->bindParam(':idPrevenda', $idPrevendaAtual, PDO::PARAM_INT);
    $pre_insere_prevenda->bindParam(':meioPagamento', $meioPagamento, PDO::PARAM_STR);
    $pre_insere_prevenda->execute();
    //echo ok em json
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'message' => 'Inserido com sucesso.']);
}

?>