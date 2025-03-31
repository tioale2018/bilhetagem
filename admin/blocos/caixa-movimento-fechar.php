<?php

if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

session_start();
include_once("../inc/conexao.php");
$horaagora = time();

$dataRelata = htmlspecialchars($_POST['d'], ENT_QUOTES, 'UTF-8');

//data anterior
/*
$dataAnterior = date('Y-m-d', strtotime('-1 day', strtotime($dataRelata)));

$sql_buscadataAnterior = "select * from tbcaixa_abre where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='$dataAnterior'";
$pre_buscadataAnterior = $connPDO->prepare($sql_buscadataAnterior);
$pre_buscadataAnterior->execute();

if ($pre_buscadataAnterior->rowCount() > 0) {
*/
$sql_atualizacaixa = "update tbcaixa_abre set status=2, datahora_fecha='$horaagora', usuario_fecha=".$_SESSION['user_id']." where status=1 and id=:datarelata";
$pre_atualizacaixa = $connPDO->prepare($sql_atualizacaixa);
$pre_atualizacaixa->bindParam(':datarelata', $dataRelata, PDO::PARAM_INT);
if ($pre_atualizacaixa->execute()) {
    echo json_encode(array('status' => '1'));
    exit;
}


?>