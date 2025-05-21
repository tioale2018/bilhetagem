<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$nome          = $_POST['nome'];
$nascimento    = convertDateToYMD($_POST['nascimento']);
$vinculo       = $_POST['vinculo'];
$pacote        = $_POST['pacote'];
$idresponsavel = $_POST['idresponsavel'];
$idprevenda    = $_POST['idprevenda'];
$idvinculado   = $_POST['idvinculado'];
$identrada     = $_POST['identrada'];

$lembrar       = (isset($_POST['melembrar'])?1:0);

$idresponsavelSessao = $_SESSION['dadosResponsavel'][0]['id_responsavel'];



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

