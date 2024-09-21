<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
include_once('../inc/conexao.php');
include_once('../inc/funcoes-gerais.php');

if (!isset($_SESSION['evento'])) {
    echo json_encode(['error' => 'session_expired']);
    exit;
}

$horaagora = time();

function extrairIds($array) {
    $ids = [];

    // Itera sobre o array para coletar os valores de 'id_prevenda'
    foreach ($array as $item) {
        if (isset($item['id_prevenda'])) {
            $ids[] = $item['id_prevenda'];
        }
    }

    // Remove duplicatas e ordena os valores
    $ids = array_unique($ids);
    sort($ids);

    // Converte o array em uma string separada por vírgulas
    return implode(', ', $ids);
}

/* Adicionado em 19/09/24 19:52. 
Descrição: o sql abaixo tem como objetivo corrigir o erro do status de prevenda para 1, quando a venda acontecer e por algum motivo o status não for alterado para 2
este erro tem acontecido aparentemente em internet lenta. Somente altera o status das prevendas no dia atual. */
$diahoje_between = strtotime(date('Y-m-d', $horaagora) . " 00:00:00") . " and ". strtotime(date('Y-m-d', $horaagora) . " 23:59:59");

// $sql_busca_correcao = "select tbprevenda.id_prevenda from 
//                        tbprevenda inner join 
//                        tbentrada on tbprevenda.id_prevenda=tbentrada.id_prevenda
//                        where tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.prevenda_status=1 and tbentrada.previnculo_status=3 and tbprevenda.datahora_efetiva BETWEEN ". $diahoje_between ;
$sql_busca_correcao = "select tbprevenda.id_prevenda from 
                       tbprevenda inner join 
                       tbentrada on tbprevenda.id_prevenda=tbentrada.id_prevenda
                       where tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.prevenda_status=1 and tbentrada.previnculo_status=3 and tbprevenda.datahora_efetiva >". strtotime(date('Y-m-d', $horaagora) . " 00:00:00") . " group by tbprevenda.id_prevenda";

$pre_busca_correcao = $connPDO->prepare($sql_busca_correcao);
$pre_busca_correcao->execute();

if ($pre_busca_correcao->rowCount() > 0) {
    $row_busca_correcao = $pre_busca_correcao->fetchAll();
    //alternativa 1
    /*
    foreach ($row_busca_correcao as $key => $value) {
        $sql_correcao = "update tbprevenda set prevenda_status=2 where id_prevenda=:idprevenda";
        $pre_correcao = $connPDO->prepare($sql_correcao);
        $pre_correcao->bindParam(':idprevenda', $value['id_prevenda'], PDO::PARAM_INT);
        $pre_correcao->execute();
    }
        */
        //alternativa 2
        $resultado = extrairIds($row_busca_correcao);
        $sql_correcao = "update tbprevenda set prevenda_status=2 where id_prevenda in ($resultado)";
        // die($sql_correcao);
        $pre_correcao = $connPDO->prepare($sql_correcao);
        
        if ($pre_correcao->execute()) {
            $log_correcao = "insert into tblogcorrecao (corrigidos, usuario, data, evento) values ('$resultado', ". $_SESSION['user_id'] . ", '$horaagora', " . $_SESSION['evento_selecionado'] . ")";
            $pre_log_correcao = $connPDO->prepare($log_correcao);
            $pre_log_correcao->execute();
        }

}


/*

$sql_correcao = "update tbprevenda as tb1
inner join tbentrada as tb2 on tb1.id_prevenda = tb2.id_prevenda
set tb1.prevenda_status=2
where 
tb1.prevenda_status=1 and tb2.previnculo_status=3 and tb1.datahora_efetiva BETWEEN ". $diahoje_between;
die($sql_correcao);
$pre_correcao = $connPDO->prepare($sql_correcao);
$pre_correcao->execute();
*/


/* fim da correção  */


/*
$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbpacotes.descricao as nomepacote
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3  and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." order by tbentrada.datahora_entra";
*/

$datasHoje = geraDatasSQL(date('Y-m-d'));

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbpacotes.descricao as nomepacote, tbprevenda.prevenda_status
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3 and tbprevenda.prevenda_status in (2,5) and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.datahora_efetiva between ". $datasHoje['start'] ." and ". $datasHoje['end'] . " order by tbentrada.datahora_entra";

// die($sql);

$pre = $connPDO->prepare($sql);

$pre->execute();
$row = $pre->fetchAll();

?>


<div class="body project_report">
    <div>
        <p>Total de pessoas: <?= count($row) ?></p>
        <p>Capacidade: <?= $_SESSION['evento']['capacidade'] ?> </p>
    </div>
        <div class="table-responsive">
            <table class="table m-b-0 table-hover tabela-lista-controle">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Nome/Responsável</th>
                        <th>Hora entrada</th>
                        <th>Hora saída</th>
                        <th>Pacote</th>
                        <th>Tempo Decorrido</th>
                        <th>Saída</th>
                    </tr>
                </thead>
                <tbody>
                <?php  if (count($row) > 0) {  ?>
                        <?php  foreach ($row as $key => $value) {  ?>
                            <tr>
                                <td><?= $value['id_prevenda'] ?></td>
                                <td class="project-title">
                                    <h6><?= obterNomeESobrenome($value['nome']) . '('.calcularIdade($value['nascimento']).' Anos)' ?></h6>
                                    <small>Resp.: <?= $value['responsavel'] ?></small>
                                </td>
                                <td>
                                    <div class="hora-entrada"><?= date('H:i:s', $value['datahora_entra']) ?></div>
                                </td>
                                <td>
                                    <div class="hora-saida"><?= somarMinutos($value['datahora_entra'], $value['duracao']) ?></div>
                                    <!-- <small>+<?= calculaDuracao($value['tolerancia']); ?></small> -->
                                </td> 
                                <td>
                                    <div class="nome-pacote"><?= calculaDuracao($value['duracao']); ?></div>
                                    <small><?= $value['nomepacote'] ?></small>
                                </td>

                                <td><span class="tdecorrido"></span></td>
                                <td class="project-actions">
                                    <button data-idprevenda="<?= $value['id_prevenda'] ?>" type="button" data-toggle="modal" data-target="#modalSaida" class="btn btn-neutral btn-sm btnModalSaida"><i class="zmdi zmdi-sign-in"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="6" style="text-align: center">Nenhum resultado encontrado</td></tr>
                    <?php } ?>
                    
                </tbody>
            </table>
        </div>
</div>

<script src="./js/controle-lista.js"></script>
<script>
    
    $(document).ready(function(){

    setInterval(updateElapsedTime, 1000);
    updateElapsedTime();


        $('.btnModalSaida').on('click', function(){
            $('.loader-aguarde').show();

            let i = $(this).data('idprevenda');
            $('#modalSaida').modal();
            
            $.post("./blocos/busca-prevenda.php",{p:i}, function(data){
                let dados = JSON.parse(data);

                $('#nomeresponsavel').html(dados[0]['responsavel']);
                $('#cpf').html(formatCPF(dados[0]['cpf']));
                $('#tel1').html(dados[0]['telefone1']);
                $('#tel2').html(dados[0]['telefone2']);
                $('#idprevenda').val(dados[0]['id_prevenda']);
                $('#tempo_agora').val(dados[0]['temponow']);
                $('#reprint').data('printprevenda', dados[0]['id_prevenda']);

                $('#tabelaDados').empty();
            
                // Criar a tabela
                let tabela = $('<table>').addClass('table table-bordered m-b-0 table-hover');
                
                // Criar o cabeçalho da tabela
                let cabecalho = $('<thead>').append(
                    $('<tr>').append(
                        $('<th>').text('#'),
                        $('<th>').text('Nome'),
                        $('<th>').text('H.Entrada'),
                        $('<th>').text('H.Saída'),
                        $('<th>').text('Pacote'),
                        $('<th>').text('Permanência (min)')
                    )
                );
                
                tabela.append(cabecalho);
                let corpoTabela = $('<tbody>');

                dados.forEach(function(dado) {
                    let checkboxDiv   = $('<div>').addClass('checkbox');
                    let checkboxInput = $('<input>').attr('type', 'checkbox').addClass('checkbox chkmark').attr('name', 'chkvinculado[]').attr('id', 'checkbox_' + dado.id_entrada).attr('value', dado.id_vinculado).prop('checked', true);
                    let checkboxLabel  = $('<label>').attr('for', 'checkbox_' + dado.id_entrada);
                    
                    checkboxDiv.append(checkboxInput, checkboxLabel);
                    // let excedeLinha = 0;
                    // excedeLinha = dado.min_adicional * dado.tempoExcedenteMinutos;
                    // total = total + excedeLinha;

                    // let formattedValue = excedeLinha.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    let permanencia = calcularPermanenciaEmMinutos(dado.datahora_entra, dado.temponow);
                    
                    let linha = $('<tr>').append(            
                        $('<td>').append(checkboxDiv),
                        $('<td>').text(dado.nome),
                        $('<td>').text(dado.horaEntrada),
                        $('<td>').text(dado.horaSaida),
                        $('<td>').text(dado.duracao),
                        $('<td>').text(permanencia)
                        
                    );
                    corpoTabela.append(linha);
                });
                
                // Adicionar corpo da tabela à tabela
                tabela.append(corpoTabela);
                
                // Adicionar a tabela à div desejada
                $('#tabelaDados').append(tabela);

                $('.loader-aguarde').hide();
            });  
                
        });

        // $('#tabelaDados').dataTable();
        $('.tabela-lista-controle').dataTable({
            paging: false,
            order: [[2, 'asc']]
        });
        
    });
</script>