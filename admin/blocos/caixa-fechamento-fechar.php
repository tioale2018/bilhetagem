<?php
session_start();
include_once('../inc/funcoes.php'); 

if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('../inc/conexao.php');
include_once('../inc/caixa-salvar.php');

$horaAgora = time();

$sql_fecha = "update tbcaixa_diario set usuario_fecha=".$_SESSION['user_id'].", datahora_fecha=$horaAgora, status=2 where id=$codcaixaform";
$pre_fecha = $connPDO->prepare($sql_fecha);
$pre_fecha->execute();

// echo $sql_fecha;
echo json_encode(array('status' => '1'));
exit;
?>