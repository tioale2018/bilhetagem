<?php
if (($_SERVER['REQUEST_METHOD']!="POST") || (!isset($_POST['participante']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    
    die(0);
}
session_start();
include('../inc/conexao.php');

$identrada = $_POST['participante'];
$horaagora = time();

$sql_verifica_responsavel = "SELECT tbentrada.id_vinculado, tbvinculados.id_responsavel FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
WHERE tbentrada.id_entrada=:identrada";

$pre_verifica_responsavel = $connPDO->prepare($sql_verifica_responsavel);
$pre_verifica_responsavel->bindParam(':identrada', $identrada, PDO::PARAM_INT);
$pre_verifica_responsavel->execute();

$row_verifica_responsavel = $pre_verifica_responsavel->fetchAll();
if ($row_verifica_responsavel[0]['id_responsavel']==$_SESSION['dadosResponsavel'][0]['id_responsavel']) {
    $sql_atualiza_termo_participante = "update tbentrada set autoriza=1, datahora_autoriza='$horaagora' where id_entrada=:identrada";
    $pre_atualiza_termo_participante = $connPDO->prepare($sql_atualiza_termo_participante);
    $pre_atualiza_termo_participante->bindParam(':identrada', $identrada, PDO::PARAM_INT);
    $pre_atualiza_termo_participante->execute();
}



?>