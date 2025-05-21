<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$nome          = htmlspecialchars($_POST['nome']);
$nascimento    = htmlspecialchars(convertDateToYMD($_POST['nascimento']));
$vinculo       = htmlspecialchars($_POST['vinculo']);
// $pacote        = htmlspecialchars($_POST['pacote']);
$perfil        = htmlspecialchars($_POST['perfil']);
$idresponsavel = htmlspecialchars($_POST['idresponsavel']);
$idprevenda    = htmlspecialchars($_POST['idprevenda']);
$lembrarme     = htmlspecialchars((isset($_POST['lembrarme']) ? 1 : 0));

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