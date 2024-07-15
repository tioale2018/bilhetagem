<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();

include('../inc/conexao.php');
include('../inc/funcoes.php');


// var_dump($_SESSION['dadosResponsavel']);

$nome          = $_POST['nome'];
// $cpf           = $_POST['cpf'];
$telefone1     = $_POST['telefone1'];
$telefone2     = $_POST['telefone2'];
$email         = $_POST['email'];
$idresponsavel = $_SESSION['dadosResponsavel'][0]['id_responsavel'];

// $sql_atualiza_responsavel = "update tbresponsavel set nome=:nome, cpf=:cpf, email=:email, telefone1=:telefone1, telefone2=:telefone2 where id_responsavel=:id";
$sql_atualiza_responsavel = "update tbresponsavel set nome=:nome, email=:email, telefone1=:telefone1, telefone2=:telefone2 where id_responsavel=:id";
$pre_atualiza_responsavel = $connPDO->prepare($sql_atualiza_responsavel);
$pre_atualiza_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
// $pre_atualiza_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':telefone2', $telefone2, PDO::PARAM_STR);
$pre_atualiza_responsavel->bindParam(':id', $idresponsavel, PDO::PARAM_INT);
$pre_atualiza_responsavel->execute();

$_SESSION['dadosResponsavel'] = procuraResponsavel($cpf);

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