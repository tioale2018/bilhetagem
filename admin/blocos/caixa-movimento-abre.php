<?php
include_once('../inc/funcoes.php'); 

if ( $_SERVER['REQUEST_METHOD']!="POST" || (!isValidDate($_POST['d'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

session_start();
include_once("../inc/conexao.php");
$horaagora = time();

//verifica se o caixa de hoje ja foi aberto
//verifica se o caixa do dia anterior ja foi fechado
//caso o caixa do dia anterior nao tenha sido fechado, informe que poderão inconsistencias erros e segue para o caixa do dia atual
// ao fechar o caixa do dia atual, não permitir fechar caso o do dia anterior exista e nao tenha sido fechado
// caso não exista (salva em log), ou se estiver fechado, permitir fechar o caixa do dia atual

$dataRelata = $_POST['d'];

//data anterior
$dataAnterior = date('Y-m-d', strtotime('-1 day', strtotime($dataRelata)));

$sql_buscadata = "select * from tbcaixa_abre where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='$dataRelata'";
$pre_buscadata = $connPDO->prepare($sql_buscadata);
$pre_buscadata->execute();

if ($pre_buscadata->rowCount() == 0) {
    //caso o caixa da data selecionada nao tenha sido aberto, verifica se o dia anterior existe   

    $sql_abre_caixa = "insert into tbcaixa_abre (idevento, datacaixa, status, usuario_abre, datahora_abre) values (".$_SESSION['evento_selecionado'].", '$dataRelata', 1, ".$_SESSION['user_id'].", $horaagora)";
    $pre_abre_caixa = $connPDO->prepare($sql_abre_caixa);
    
    //se tudo ok, retonar um json com sucesso
    //verifica se sql foi executado com sucesso
    if ($pre_abre_caixa->execute()) {
        echo json_encode(array('status' => '1'));
        //exit;
    }

}
?>