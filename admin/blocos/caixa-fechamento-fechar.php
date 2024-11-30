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


$sis_cartao   = isset($_POST['sis_cartao']) ? filter_input(INPUT_POST, 'sis_cartao', FILTER_SANITIZE_STRING) : '';
$sis_pix      = isset($_POST['sis_pix']) ? filter_input(INPUT_POST, 'sis_pix', FILTER_SANITIZE_STRING) : '';
$sis_dinheiro = isset($_POST['fval_dinheiro']) ? filter_input(INPUT_POST, 'fval_dinheiro', FILTER_SANITIZE_STRING) : '';
$diario_id    = isset($_POST['diario']) ? filter_input(INPUT_POST, 'diario', FILTER_SANITIZE_STRING) : '';

$fix_cartao = formatToFloat($sis_cartao);
$fix_pix    = formatToFloat($sis_pix);
$fix_dinheiro = formatToFloat($sis_dinheiro);


$horaAgora = time();

$sql_atualizaform = "update tbcaixa_formulario set sis_vendadin=$fix_dinheiro, sis_vendapix=$fix_pix, sis_vendacar=$fix_cartao, status=2, datahora_lastupdate=$horaAgora where id=$codcaixaform";
$pre_atualizaform = $connPDO->prepare($sql_atualizaform);

$sql_fecha = "update tbcaixa_diario set usuario_fecha=".$_SESSION['user_id'].", datahora_fecha=$horaAgora, status=2 where id=$diario_id";
$pre_fecha = $connPDO->prepare($sql_fecha);
$pre_fecha->execute();

// echo $sql_fecha;
echo json_encode(array('status' => '1'));
exit;
?>