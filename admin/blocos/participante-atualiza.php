<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$nome          = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
$nascimento    = convertDateToYMD(htmlspecialchars($_POST['nascimento'], ENT_QUOTES, 'UTF-8'));
$vinculo       = htmlspecialchars($_POST['vinculo'], ENT_QUOTES, 'UTF-8');
$perfil        = htmlspecialchars($_POST['perfil'], ENT_QUOTES, 'UTF-8');
// $pacote        = htmlspecialchars($_POST['pacote'], ENT_QUOTES, 'UTF-8');
$idresponsavel = htmlspecialchars($_POST['idresponsavel'], ENT_QUOTES, 'UTF-8');
$idprevenda    = htmlspecialchars($_POST['idprevenda'], ENT_QUOTES, 'UTF-8');
$idvinculado   = htmlspecialchars($_POST['idvinculado'], ENT_QUOTES, 'UTF-8');
$identrada     = htmlspecialchars($_POST['identrada'], ENT_QUOTES, 'UTF-8');

$lembrar       = (isset($_POST['melembrar'])?1:0);

$sql1 = "update tbvinculados set  nome=:nome, nascimento=:nascimento, tipo=:tipo, lembrar=:lembrar where id_vinculado=:idvinculado";

$pre1 = $connPDO->prepare($sql1);
$pre1->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre1->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
$pre1->bindParam(':tipo', $vinculo, PDO::PARAM_STR);
$pre1->bindParam(':lembrar', $lembrar, PDO::PARAM_INT);
$pre1->bindParam(':idvinculado', $idvinculado, PDO::PARAM_INT);

$pre1->execute();

// $sql2 = "update tbentrada set perfil_acesso=:perfil, id_pacote=:pacote where id_entrada=:identrada";
$sql2 = "update tbentrada set perfil_acesso=:perfil where id_entrada=:identrada";

$pre2 = $connPDO->prepare($sql2);
// $pre2->bindParam(':pacote', $pacote, PDO::PARAM_INT);
$pre2->bindParam(':perfil', $perfil, PDO::PARAM_INT);
$pre2->bindParam(':identrada', $identrada, PDO::PARAM_INT);

$pre2->execute();
?>