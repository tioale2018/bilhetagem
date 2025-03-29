<?php
session_start();

if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('../inc/conexao.php');
include_once('../inc/funcoes-calculo.php');
include_once('../inc/funcao-tempo.php');

$idprevenda = $_POST['idprevenda'];
$pgto       = $_POST['pgto'];
$tipopgto   = (isset($_POST['tipopgto']) ? $_POST['tipopgto'] : 0); //$_POST['tipopgto'];
$horaagora  = time();
$usuario = $_SESSION['user_id'];

if (!isset($_POST['pagasaida'])) {
    //procedimento para o pagamento na entrada
    $numqueries = 0;
    $dois       = 2;

    $sql_status_prevenda = "update tbprevenda set datahora_efetiva=:horaagora, prevenda_status=:dois where id_prevenda=:idprevenda";
    $pre_status_prevenda = $connPDO->prepare($sql_status_prevenda);
    $pre_status_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_status_prevenda->bindParam(':dois', $dois, PDO::PARAM_INT);
    $pre_status_prevenda->bindParam(':horaagora', $horaagora, PDO::PARAM_STR);
    // $pre_status_prevenda->execute();
    if($pre_status_prevenda->execute()) {
        $numqueries++;
    };

    $sql_status_entrada = "update tbentrada set previnculo_status=3, datahora_entra=:horaagora  where id_prevenda=:idprevenda and previnculo_status=1";
    $pre_status_entrada = $connPDO->prepare($sql_status_entrada);
    $pre_status_entrada->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_status_entrada->bindParam(':horaagora', $horaagora, PDO::PARAM_STR);
    // $pre_status_entrada->execute();
    if($pre_status_entrada->execute()) {
        $numqueries++;
    };
    
    //tpcobranca=1 para cobrança na entrada
    $tpcobranca = 1; 
    $sql_efetua_pgto = "insert into tbfinanceiro (id_prevenda, tp_cobranca, valor, forma_pgto, hora_pgto, usuario) values (:idprevenda, $tpcobranca, :valor, :forma_pgto, '$horaagora', $usuario)";
    
    $pre_efetua_pgto = $connPDO->prepare($sql_efetua_pgto);
    $pre_efetua_pgto->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_efetua_pgto->bindParam(':valor', $pgto, PDO::PARAM_STR);
    $pre_efetua_pgto->bindParam(':forma_pgto', $tipopgto, PDO::PARAM_STR);
    // $pre_efetua_pgto->bindParam(':horapgto', $horaagora, PDO::PARAM_STR);
    
    // $pre_efetua_pgto->execute();
    if($pre_efetua_pgto->execute()) {
        $numqueries++;
    };
    $idFinanceiro = $connPDO->lastInsertId();

    //echo $numqueries;
        
     $financeiro_detalha = json_decode($_POST['pgtodetalha'], true); // true para obter um array associativo
    //$financeiro_detalha = json_decode($_SESSION['financeiro_detalha'], true);
    try {
        $connPDO->beginTransaction(); // Inicia a transação
        $sql_financeiro_detalha = "INSERT INTO tbfinanceiro_detalha (idprevenda, identrada, idfinanceiro, datahorapgto, valorpgto, tipopgto, pgtoinout) VALUES (:idprevenda, :identrada, $idFinanceiro, :datahorapgto, :valorpgto, :tipopgto, 1)";
        $stmt = $connPDO->prepare($sql_financeiro_detalha);
    
        $financeiro_detalha = json_decode($_POST['pgtodetalha'], true); // true para obter um array associativo
        //$financeiro_detalha = json_decode($_SESSION['financeiro_detalha'], true);
    
        $num_records = count($financeiro_detalha['identrada']);
        for ($i = 0; $i < $num_records; $i++) {
            $stmt->bindParam(':idprevenda', $idprevenda); // Você precisa definir o valor de idprevenda
            $stmt->bindParam(':identrada', $financeiro_detalha['identrada'][$i]);
            $stmt->bindParam(':datahorapgto', $horaagora); 
            $stmt->bindParam(':valorpgto', $financeiro_detalha['apagar'][$i]);
            $stmt->bindParam(':tipopgto', $tipopgto);
            
            $stmt->execute();
            // $numqueries++;
        }
    
        $connPDO->commit(); // Confirma a transação
    } catch (Exception $e) {
        $connPDO->rollBack(); // Reverte a transação em caso de erro
        throw $e; // Re-levanta a exceção

    }

    $codeErro =  ($numqueries>=3?1:0);
    echo json_encode(['error' => $codeErro]);

} elseif (isset($_POST['pagasaida'])) {
    //procedimento pagamento saída
    $idprevenda         = $_POST['idprevenda'];
    $horafinaliza       = $_POST['horafinaliza'];
    $vinculados         = explode(',', $_POST['vinculados']);
    $financeiro_detalha = json_decode($_POST['pgtodetalha'], true); // true para obter um array associativo
    // die(var_dump($financeiro_detalha));

    $sql_verifca_participantes = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome AS responsavel, tbresponsavel.telefone1, tbresponsavel.telefone2, tbpacotes.min_adicional AS adicionalpacote, :horafinaliza AS datahora_saida
    FROM tbentrada 
    INNER JOIN tbvinculados ON tbentrada.id_vinculado = tbvinculados.id_vinculado
    INNER JOIN tbpacotes ON tbentrada.id_pacote = tbpacotes.id_pacote
    INNER JOIN tbprevenda ON tbentrada.id_prevenda = tbprevenda.id_prevenda
    INNER JOIN tbresponsavel ON tbprevenda.id_responsavel = tbresponsavel.id_responsavel
    WHERE tbentrada.previnculo_status = 3 AND tbentrada.id_prevenda = :idprevenda";

    $pre_verifca_participantes = $connPDO->prepare($sql_verifca_participantes);
    $pre_verifca_participantes->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_verifca_participantes->bindParam(':horafinaliza', $horafinaliza, PDO::PARAM_STR);
    $pre_verifca_participantes->execute();
    $row_verifca_participantes = $pre_verifca_participantes->fetchAll();

    //prepara o array para levantar as variaveis por inidivudo
    foreach ($row_verifca_participantes as $key => $value) {
        # code...
        $i[$value['id_vinculado']]['nome']           = $value['nome'];
        $i[$value['id_vinculado']]['datahora_entra'] = $value['datahora_entra'];
        $i[$value['id_vinculado']]['datahora_saida'] = $value['datahora_saida'];
        $i[$value['id_vinculado']]['duracao']        = $value['duracao'];
        $i[$value['id_vinculado']]['tolerancia']     = $value['tolerancia'];
        $i[$value['id_vinculado']]['id_entrada']     = $value['id_entrada'];
    }

    $status = ($pre_verifca_participantes->rowCount() == count($vinculados)?6:5);
    
    $sql_atualiza_prevenda = "update tbprevenda set prevenda_status=$status, datahora_efetiva_saida='$horaagora' where id_prevenda=:idprevenda";
    $pre_atualiza_prevenda = $connPDO->prepare($sql_atualiza_prevenda);
    $pre_atualiza_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_atualiza_prevenda->execute();

    //calcularExcedente($duracao, $tolerancia, $permanencia);

    foreach ($vinculados as $key => $value) {
        //echo $i[$value]['nome'];
        
        $horaEntrada = $i[$value]['datahora_entra'];
        $horaSaida   = $i[$value]['datahora_saida'];
        $pacote      = $i[$value]['duracao'];
        $tolerancia  = $i[$value]['tolerancia'];
        $idEntrada   = $i[$value]['id_entrada'];
        $aPagar      = $financeiro_detalha['apagar'][$key];

        $teste       = $financeiro_detalha['apagar'][$key];
        
        $a = calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia);
        $tempoExcedente     = $a['tempoExcedenteMinutos'];
        $tempoPermanencia   = $a['tempoPermanenciaMinutos'];
        $pgtoExtra          = ($a['tempoExcedenteMinutos']>0?1:0);
        
        //tbentrada set previnculo_status=4, datahora_saida=time(), tempo_excede=tempo, pgto_extra=0/1 
        $sql_atualiza_entrada = "update tbentrada set previnculo_status=4, datahora_saida=$horaSaida, tempo_excede=$tempoExcedente, tempo_permanencia=$tempoPermanencia, pgto_extra=$pgtoExtra, pgto_extra_valor=:pgto where id_entrada=$idEntrada";
        // die($sql_atualiza_entrada);

        $pre_atualiza_entrada = $connPDO->prepare($sql_atualiza_entrada);
        $pre_atualiza_entrada->bindParam(':pgto', $aPagar, PDO::PARAM_STR);
        $pre_atualiza_entrada->execute();
    }
    
    //insere o valor do pagamento

    $valor       = $pgto;
    $forma_pgto  = $tipopgto;

    //tpcobranca 4 pagamento na saida com pgto extra
    //tpcobranca 2 pagamento na saida sem pgto extra
    $tpcobranca  = ($valor>0?4:2);

    $sql_movimento_pagamento = "insert into tbfinanceiro (id_prevenda, tp_cobranca, valor, forma_pgto, hora_pgto, usuario) values (:id_prevenda, $tpcobranca, :valor, :forma_pgto, '$horaagora', $usuario)";
    $pre_movimento_pagamento = $connPDO->prepare($sql_movimento_pagamento);
    $pre_movimento_pagamento->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
    $pre_movimento_pagamento->bindParam(':valor', $valor, PDO::PARAM_STR);
    $pre_movimento_pagamento->bindParam(':forma_pgto', $forma_pgto, PDO::PARAM_INT);
    // $pre_movimento_pagamento->bindParam(':hora_pgto', $horaagora, PDO::PARAM_STR);
    $pre_movimento_pagamento->execute();

    $idFinanceiro = $connPDO->lastInsertId();

    //processar o detalhamento do pagamento em tbfinanceiro_detalha - pagamento na saída
    
    //$financeiro_detalha = json_decode($_SESSION['financeiro_detalha'], true);
    try {
        // Supondo que $pdo seja a instância PDO já configurada
        $connPDO->beginTransaction(); // Inicia a transação

        // Prepara a declaração uma vez
        $sql_financeiro_detalha = "INSERT INTO tbfinanceiro_detalha (idprevenda, identrada, idfinanceiro, datahorasaida, permanencia, datahorapgto, valorpgto, tipopgto, pgtoinout) VALUES (:idprevenda, :identrada, $idFinanceiro, :datahorasaida, :permanencia, :datahorapgto, :valorpgto, :tipopgto, 2)";
        $stmt = $connPDO->prepare($sql_financeiro_detalha);

        // Decodifica os dados JSON recebidos via POST
        $financeiro_detalha = json_decode($_POST['pgtodetalha'], true); // true para obter um array associativo
        //$financeiro_detalha = json_decode($_SESSION['financeiro_detalha'], true);

        // Itera através dos dados para realizar os inserts
        $num_records = count($financeiro_detalha['identrada']);
        for ($i = 0; $i < $num_records; $i++) {
            // Vincula os valores e executa a declaração
            $stmt->bindParam(':idprevenda', $idprevenda); // Você precisa definir o valor de idprevenda
            $stmt->bindParam(':identrada', $financeiro_detalha['identrada'][$i]);
            $stmt->bindParam(':datahorasaida', $financeiro_detalha['datahora_saida'][$i]);
            $stmt->bindParam(':permanencia', $financeiro_detalha['tempoPermanece'][$i]);
            $stmt->bindParam(':datahorapgto', $horaagora); 
            $stmt->bindParam(':valorpgto', $financeiro_detalha['apagar'][$i]);
            $stmt->bindParam(':tipopgto', $tipopgto);
            
            $stmt->execute();
        }

        $connPDO->commit(); // Confirma a transação
    } catch (Exception $e) {
        $connPDO->rollBack(); // Reverte a transação em caso de erro
        throw $e; // Re-levanta a exceção
    }
//----------- fim detalhamento -------------


    //verifica qunatos entraram
    //se todos estiverem saindo
    //-- update prevenda status=6
    //senao
    //-- update prevenda status=5
/*
por individuo:
variavel1 = tempo excedente
variavel2 = se pgto_extra então 1 senao 0
*/
    //tbmovimento
    //insere valor do pagamento
    //tbentrada set previnculo_status=4, datahora_saida=time(), tempo_excede=tempo, pgto_extra=0/1 
    //update 
    //tbmovimento-saida
    //insere id de quem está saindo

}
?>