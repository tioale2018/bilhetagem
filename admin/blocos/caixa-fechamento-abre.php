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
    $stmt = $connPDO->prepare("SELECT count(tbentrada.id_pacote) as total_vendido FROM tbentrada inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda where tbentrada.id_pacote>0 and tbprevenda.id_evento=:idevento and tbentrada.datahora_entra BETWEEN :start AND :end");
    $stmt->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
    $stmt->bindParam(':start', $dataSql['start'], PDO::PARAM_INT);
    $stmt->bindParam(':end', $dataSql['end'], PDO::PARAM_INT);
    $stmt->execute();
    $row_buscatickets = $stmt->fetch(PDO::FETCH_ASSOC);

    $totaltickets_vendidos = $row_buscatickets['total_vendido'];
    

//data anterior
$dataAnterior = date('Y-m-d', strtotime('-1 day', strtotime($dataRelata)));

$sql_buscadata = "select * from tbcaixa_diario where status>0 and idevento=:idevento and datacaixa=:datacaixa";
$pre_buscadata = $connPDO->prepare($sql_buscadata);
$pre_buscadata->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
$pre_buscadata->bindParam(':datacaixa', $dataRelata, PDO::PARAM_STR);
$pre_buscadata->execute();

if ($pre_buscadata->rowCount() == 0) {
    //caso o caixa da data selecionada nao tenha sido aberto, verifica se o dia anterior existe  

    $sql_buscadata = "select * from tbcaixa_diario where status>0 and idevento=:idevento and datacaixa=:datacaixa"; 
    $pre_buscadata = $connPDO->prepare($sql_buscadata);
    $pre_buscadata->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
    $pre_buscadata->bindParam(':datacaixa', $dataAnterior, PDO::PARAM_STR);
    $pre_buscadata->execute();

    $saldo_diaanterior = 0;

    if ($pre_buscadata->rowCount() > 0) {
        
        $row_buscadata = $pre_buscadata->fetch(PDO::FETCH_ASSOC);
        $id_diaanterior = $row_buscadata['id'];

        $sql_form_anterior = "select * from tbcaixa_formulario where status>0 and idevento=:idevento and idcaixadiario=:idcaixadiario and data_caixa=:data_caixa";
        $pre_form_anterior = $connPDO->prepare($sql_form_anterior);
        $pre_form_anterior->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
        $pre_form_anterior->bindParam(':idcaixadiario', $id_diaanterior, PDO::PARAM_INT);
        $pre_form_anterior->bindParam(':data_caixa', $dataAnterior, PDO::PARAM_STR);
        $pre_form_anterior->execute();

        if ($pre_form_anterior->rowCount() > 0) {
            $row_form_anterior = $pre_form_anterior->fetch(PDO::FETCH_ASSOC);
            $saldo_diaanterior = $row_form_anterior['val_final']; //sangria
        }

    }



    $sql_abrecaixa_diario = "insert into tbcaixa_diario (idevento, datacaixa, usuario_abre, datahora_abre) values (:idevento, :datacaixa, :usuario_abre, :datahora_abre)";
    $pre_abrecaixa_diario = $connPDO->prepare($sql_abrecaixa_diario);
    $pre_abrecaixa_diario->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
    $pre_abrecaixa_diario->bindParam(':datacaixa', $dataRelata, PDO::PARAM_STR);
    $pre_abrecaixa_diario->bindParam(':usuario_abre', $_SESSION['user_id'], PDO::PARAM_INT);
    $pre_abrecaixa_diario->bindParam(':datahora_abre', $horaagora, PDO::PARAM_INT);
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

    $sql_insere_entrada = "INSERT INTO tbcaixa_formulario (idevento, idcaixadiario, idusuario, datahora_lastupdate, sis_totaltickets, total_tickets, data_caixa, sis_datasaldo, sis_abrecaixa) VALUES (:idevento, :idcaixadiario, :idusuario, :datahora_lastupdate, :sis_totaltickets, :total_tickets, :data_caixa, :sis_datasaldo, :sis_abrecaixa)";
    $pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
    $pre_insere_entrada->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
    $pre_insere_entrada->bindParam(':idcaixadiario', $lastcaixadiario_id, PDO::PARAM_INT);
    $pre_insere_entrada->bindParam(':idusuario', $_SESSION['user_id'], PDO::PARAM_INT);
    $pre_insere_entrada->bindParam(':datahora_lastupdate', $horaagora, PDO::PARAM_INT);
    $pre_insere_entrada->bindParam(':sis_totaltickets', $totaltickets_vendidos, PDO::PARAM_INT);
    $pre_insere_entrada->bindParam(':total_tickets', $total_vendido, PDO::PARAM_INT);
    $pre_insere_entrada->bindParam(':data_caixa', $dataRelata, PDO::PARAM_STR);
    $pre_insere_entrada->bindParam(':sis_datasaldo', $dataAnterior, PDO::PARAM_STR);
    $pre_insere_entrada->bindParam(':sis_abrecaixa', $saldo_diaanterior, PDO::PARAM_STR);
    $pre_insere_entrada->execute();

    echo json_encode(array('status' => '1'));

}

?>