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
$tipopgto   = $_POST['tipopgto'];
$horaagora  = time();

//algoritmo para caso a saída seja zero pagamento

if (!isset($_POST['pagasaida'])) {

    //procedimento para o pagamento na entrada
    $numqueries = 0;

    /*
    $sql_busca_prevenda = "select * from tbprevenda where id_prevenda=:idprevenda";
    $pre_busca_prevenda = $connPDO->prepare($sql_busca_prevenda);
    $pre_busca_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_busca_prevenda->execute();
    $row_busca_prevenda = $pre_busca_prevenda->fetchAll();
    $status_prevenda_inicial = $row_busca_prevenda[0]['prevenda_status'];
    */


    $sql_status_prevenda = "update tbprevenda set prevenda_status=2, datahora_efetiva=:horaagora where prevenda_status=1 and id_prevenda=:idprevenda";
    $pre_status_prevenda = $connPDO->prepare($sql_status_prevenda);
    $pre_status_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
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


    $sql_efetua_pgto = "insert into tbfinanceiro (id_prevenda, tp_cobranca, valor, forma_pgto, hora_pgto) values (:idprevenda, :tp_cobranca, :valor, :forma_pgto, :horapgto)";
    $tp_cobranca = 1;
    $pre_efetua_pgto = $connPDO->prepare($sql_efetua_pgto);
    $pre_efetua_pgto->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_efetua_pgto->bindParam(':tp_cobranca', $tp_cobranca, PDO::PARAM_INT);
    $pre_efetua_pgto->bindParam(':valor', $pgto, PDO::PARAM_STR);
    $pre_efetua_pgto->bindParam(':forma_pgto', $tipopgto, PDO::PARAM_STR);
    $pre_efetua_pgto->bindParam(':horapgto', $horaagora, PDO::PARAM_STR);
    // $pre_efetua_pgto->execute();
    if($pre_efetua_pgto->execute()) {
        $numqueries++;
    };

    //echo $numqueries;
    echo ($numqueries==3?'ok':'erro');

} else {
    //procedimento pagamento saída
    // echo "pagasaida";
    $idprevenda   = $_POST['idprevenda'];
    $horafinaliza = $_POST['horafinaliza'];
    $vinculados   = explode(',', $_POST['vinculados']);

    //$sql_verifca_participantes = "SELECT * FROM tbentrada WHERE previnculo_status=3 and id_prevenda=:idprevenda";
    $sql_verifca_participantes = "select tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbresponsavel.telefone1, tbresponsavel.telefone2, tbpacotes.min_adicional as adicionalpacote, '$horafinaliza' as datahora_saida
    FROM tbentrada 
    inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
    inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
    inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
    inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
    WHERE tbentrada.previnculo_status=3 and tbentrada.id_prevenda=:idprevenda";

    $pre_verifca_participantes = $connPDO->prepare($sql_verifca_participantes);
    $pre_verifca_participantes->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
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

    //die(var_dump($i));
    //die($i[16]['nome']);

    $status = ($pre_verifca_participantes->rowCount() == count($vinculados)?6:5);
    
    $sql_atualiza_prevenda = "update tbprevenda set prevenda_status=$status where id_prevenda=:idprevenda";
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
        
        $a = calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia);
        $tempoExcedente = $a['tempoExcedenteMinutos'];
        $pgtoExtra = ($a['tempoExcedenteMinutos']>0?1:0);
        
        //tbentrada set previnculo_status=4, datahora_saida=time(), tempo_excede=tempo, pgto_extra=0/1 
        $sql_atualiza_entrada = "update tbentrada set previnculo_status=4, datahora_saida=$horaSaida, tempo_excede=$tempoExcedente, pgto_extra=$pgtoExtra, pgto_extra_valor=:pgto where id_entrada=$idEntrada";

        $pre_atualiza_entrada = $connPDO->prepare($sql_atualiza_entrada);
        $pre_atualiza_entrada->bindParam(':pgto', $idprevenda, PDO::PARAM_STR);
        $pre_atualiza_entrada->execute();

       
    }
    
    //insere o valor do pagamento

    $valor       = $pgto;
    $tp_cobranca = '4';
    $forma_pgto  = $tipopgto;



    $sql_movimento_pagamento = "insert into tbfinanceiro (id_prevenda, tp_cobranca, valor, forma_pgto, hora_pgto) values (:id_prevenda, :tp_cobranca, :valor, :forma_pgto, :hora_pgto)";
    $pre_movimento_pagamento = $connPDO->prepare($sql_movimento_pagamento);
    $pre_movimento_pagamento->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
    $pre_movimento_pagamento->bindParam(':tp_cobranca', $tp_cobranca, PDO::PARAM_INT);
    $pre_movimento_pagamento->bindParam(':valor', $valor, PDO::PARAM_STR);
    $pre_movimento_pagamento->bindParam(':forma_pgto', $forma_pgto, PDO::PARAM_INT);
    $pre_movimento_pagamento->bindParam(':hora_pgto', $horaagora, PDO::PARAM_STR);
    $pre_movimento_pagamento->execute();

    
     //echo var_dump($row_verifca_participantes);
    //echo "row count: " . $pre_verifca_participantes->rowCount() . " - vinculados: ".count($vinculados) . "<br>";

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