<?php
include_once('../inc/funcoes.php'); 

if ( $_SERVER['REQUEST_METHOD']!="POST" || (!isValidDate($_POST['d'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

function geraDatasSQL($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if ($dateTime === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }

    $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();
    $endTimestamp   = $dateTime->setTime(23, 59, 59)->getTimestamp();
    $i['start']     = $startTimestamp;
    $i['end']       = $endTimestamp;
    return $i;
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

$dataSql = geraDatasSQL($dataRelata);

$total_tickets = 0;
    $sql_buscatickets = "SELECT count(tbentrada.id_pacote) as total_vendido FROM tbentrada inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda where tbentrada.id_pacote>0 and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbentrada.datahora_entra BETWEEN ".$dataSql['start']." AND ".$dataSql['end'];
    $pre_buscatickets = $connPDO->prepare($sql_buscatickets);
    $pre_buscatickets->execute();
    $row_buscatickets = $pre_buscatickets->fetch(PDO::FETCH_ASSOC);

    $totaltickets_vendidos = $row_buscatickets['total_vendido'];
    

//data anterior
$dataAnterior = date('Y-m-d', strtotime('-1 day', strtotime($dataRelata)));

$sql_buscadata = "select * from tbcaixa_diario where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='$dataRelata'";
$pre_buscadata = $connPDO->prepare($sql_buscadata);
$pre_buscadata->execute();

if ($pre_buscadata->rowCount() == 0) {
    //caso o caixa da data selecionada nao tenha sido aberto, verifica se o dia anterior existe  

    $sql_buscadata = "select * from tbcaixa_diario where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='$dataAnterior'"; 
    $pre_buscadata = $connPDO->prepare($sql_buscadata);
    $pre_buscadata->execute();

    $saldo_diaanterior = 0;

    if ($pre_buscadata->rowCount() > 0) {
        
        $row_buscadata = $pre_buscadata->fetch(PDO::FETCH_ASSOC);
        $id_diaanterior = $row_buscadata['id'];

        $sql_form_anterior = "select * from tbcaixa_formulario where status>0 and idevento=".$_SESSION['evento_selecionado']." and idcaixadiario=$id_diaanterior and data_caixa='$dataAnterior'";
        $pre_form_anterior = $connPDO->prepare($sql_form_anterior);
        $pre_form_anterior->execute();

        if ($pre_form_anterior->rowCount() > 0) {
            $row_form_anterior = $pre_form_anterior->fetch(PDO::FETCH_ASSOC);
            $saldo_diaanterior = $row_form_anterior['val_final']; //sangria
        }

    }



    $sql_abrecaixa_diario = "insert into tbcaixa_diario (idevento, datacaixa, usuario_abre, datahora_abre) values (".$_SESSION['evento_selecionado'].", '$dataRelata', ".$_SESSION['user_id'].", $horaagora)";
    $pre_abrecaixa_diario = $connPDO->prepare($sql_abrecaixa_diario);
    $pre_abrecaixa_diario->execute();

    $lastcaixadiario_id = $connPDO->lastInsertId();
 
    /*
    $sql_buscatickets = "SELECT count(tbentrada.id_pacote) as total_vendido FROM tbentrada inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda where tbentrada.id_pacote>0 and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbentrada.datahora_entra BETWEEN ".$dataSql['start']." AND ".$dataSql['end'];
    $pre_buscatickets = $connPDO->prepare($sql_buscatickets);
    $pre_buscatickets->execute();
    $row_buscatickets = $pre_buscatickets->fetch(PDO::FETCH_ASSOC);

    $total_vendido = $row_buscatickets['total_vendido'];
    */
    $total_vendido = 0;

    $sql_insere_entrada = "insert into tbcaixa_formulario (idevento, idcaixadiario, idusuario, datahora_lastupdate, sis_totaltickets, total_tickets, data_caixa, sis_datasaldo, sis_abrecaixa) values (".$_SESSION['evento_selecionado'].",$lastcaixadiario_id,".$_SESSION['user_id'].", $horaagora, $totaltickets_vendidos, $total_vendido, '$dataRelata', '$dataAnterior', '$saldo_diaanterior')";
    $pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
    $pre_insere_entrada->execute();

    echo json_encode(array('status' => '1'));

}

?>