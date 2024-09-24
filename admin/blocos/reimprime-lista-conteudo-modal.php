<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['p'])) || (!is_numeric($_POST['p']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');
include_once('../inc/funcoes-gerais.php');

$prevenda = $_POST['p'];

$sql_busca_dados_modal = "SELECT 
    tbprevenda.id_prevenda, 
    tbprevenda.origem_prevenda, 
    tbentrada.id_entrada, 
    tbprevenda.id_evento, 
    tbprevenda.prevenda_status, 
    tbprevenda.datahora_efetiva, 
    tbresponsavel.cpf,
    tbresponsavel.nome AS nomeresponsavel, 
    tbresponsavel.telefone1,
    tbresponsavel.telefone2,
    tbentrada.id_vinculado, 
    tbvinculados.nome AS nomecrianca, 
    tbentrada.datahora_entra, 
    tbentrada.datahora_saida, 
    tbentrada.tempo_permanencia, 
    tbentrada.pct_nome, 
    tbentrada.datahora_autoriza,
    COALESCE(MAX(CASE WHEN tbfinanceiro_detalha.pgtoinout = 1 THEN tbfinanceiro_detalha.valorpgto END), 0) AS valorentrada,
    COALESCE(MAX(CASE WHEN tbfinanceiro_detalha.pgtoinout = 2 THEN tbfinanceiro_detalha.valorpgto END), 0) AS valorsaida,
    max(case when pgtoinout=1 then tipopgto end) as tipopgentrada,
    max(case when pgtoinout=2 then tipopgto end) as tipopgsaida

FROM 
    tbprevenda 
INNER JOIN 
    tbresponsavel ON tbprevenda.id_responsavel = tbresponsavel.id_responsavel 
INNER JOIN 
    tbentrada ON tbentrada.id_prevenda = tbprevenda.id_prevenda 
INNER JOIN 
    tbvinculados ON tbvinculados.id_vinculado = tbentrada.id_vinculado 
LEFT JOIN 
    tbfinanceiro_detalha ON tbentrada.id_entrada = tbfinanceiro_detalha.identrada 
    AND tbprevenda.id_prevenda = tbfinanceiro_detalha.idprevenda
WHERE 
    tbprevenda.id_evento = ".$_SESSION['evento_selecionado']." 
    AND tbprevenda.id_prevenda = :prevenda
GROUP BY 
    tbentrada.id_vinculado";

    $res_busca_dados_modal = $connPDO->prepare($sql_busca_dados_modal);
    $res_busca_dados_modal->bindParam(':prevenda', $prevenda, PDO::PARAM_INT);
    $res_busca_dados_modal->execute();
    $row_busca_dados_modal = $res_busca_dados_modal->fetchAll();


?>

<div class="modal-header">
    <div>
        <h4 class="title" id="ModalParticipantesImprimeLabel">Ticket #<?= $row_busca_dados_modal[0]['id_prevenda'] ?></h4>
    </div>
    <div>
        <span>Origem: <b><?= ($row_busca_dados_modal[0]['origem_prevenda']==1?'Online':'Balcão'); ?></b></span>
        <br>
        <span>Data execução: <b><?= date('d/m/Y H:i', $row_busca_dados_modal[0]['datahora_efetiva']) ?></b></span>
    </div>
        
        
</div>
    <div class="modal-body">
        <table class="table">
            <thead>
                <tr>
                    <th>CPF</th>
                    <th>Responsável</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= formatarCPF($row_busca_dados_modal[0]['cpf']) ?></td>
                    <td><?= $row_busca_dados_modal[0]['nomeresponsavel'] ?></td>
                    <td><?= $row_busca_dados_modal[0]['telefone1']  . ' <br> ' . $row_busca_dados_modal[0]['telefone2'] ?></td>
                </tr>
            </tbody>
        </table>

        <hr>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Participante</th>
                    <th>Pacote / Valor</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th>Permanência</th>
                    <th>Aceite termo</th>
                    <th>Valor entrada</th>
                    <th>Valor saída</th>
                    <th>Termo</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $total_entrada = 0;
            $total_saida = 0;
            foreach ($row_busca_dados_modal as $key => $value) {
                $total_entrada += $value['valorentrada'];
                $total_saida += $value['valorsaida'];



            ?>

            <tr>
                    <td><?= $value['nomecrianca'] ?></td>
                    <td><?= $value['pct_nome'] ?></td>
                    <td><?= date('H:i', $value['datahora_entra']) ?></td>
                    <td><?= date('H:i', $value['datahora_saida']) ?></td>
                    <td><?= $value['tempo_permanencia'] ?>min</td>
                    <td><?= date('d/m/Y H:i', $value['datahora_autoriza']) ?></td>
                    <td><?= number_format($value['valorentrada'], 2, ',', '.') ?></td>
                    <td><?= number_format($value['valorsaida'], 2, ',', '.') ?></td>
                    <td><a href="termo.php?ticket=1234" class="reimprime" title="Imprimir" data-tipo="1" data-ticket="1234"><i class="material-icons">print</i></a></td>
                </tr>
            <?php } ?>
                
            </tbody>
        </table>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Total</th>
                    <th>Valor</th>
                    <th>Meio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Entrada</td>
                    <td>R$ <?= number_format($total_entrada, 2, ',', '.') ?></td>
                    <td><?= $formapgto[$row_busca_dados_modal[0]['tipopgentrada']] ?></td>
                </tr>
                <tr>
                    <td>Saída</td>
                    <td>R$ <?= number_format($total_saida, 2, ',', '.') ?></td>
                    <td><?= $formapgto[$row_busca_dados_modal[0]['tipopgsaida']] ?></td>
                </tr>
            </tbody>
            
        </table>
    </div>

    <div class="modal-footer">
        <button class="btn btn-default btn-round waves-effect" data-dismiss="modal">Imprimir Ticket</button>
        <button class="btn btn-danger btn-round waves-effect" data-dismiss="modal">Fechar</button>
    </div>