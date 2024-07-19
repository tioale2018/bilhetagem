<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include('../inc/conexao.php');
include('../inc/funcoes.php');

//onde se lê "pacote" entenda perfil
$nome          = $_POST['nome'];
$nascimento    = convertDateToYMD($_POST['nascimento']);
$vinculo       = $_POST['vinculo'];
$perfil        = $_POST['pacote'];
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

$sql_insere_entrada = "insert into tbentrada (id_prevenda, id_vinculado, perfil_acesso) values (:id_prevenda, :id_vinculado, :perfil_acesso)";
$pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
$pre_insere_entrada->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':id_vinculado', $ultimo_id, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':perfil_acesso', $perfil, PDO::PARAM_INT);

$pre_insere_entrada->execute();

?>