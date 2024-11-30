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
include_once('../inc/funcoes.php');
$cpf       = $_POST['cpf'];
$tipobusca = $_POST['tipobusca'];

if ($tipobusca == 'cpf') {
    # code...
    $buscavariavel = "tbresponsavel.cpf like '%".$cpf."%'";
} elseif ($tipobusca == 'nome') {
    # code...
    $buscavariavel = "tbvinculados.nome like '%".$cpf."%'";
} elseif ($tipobusca == 'ticket') {
    # code...
    $buscavariavel = "tbprevenda.id_prevenda =".$cpf;
}


$sql_buscaentradas = "SELECT tbprevenda.id_prevenda, tbentrada.id_entrada as identrada, tbvinculados.nome as nomecrianca, tbresponsavel.nome as nomeresponsavel, tbprevenda.data_acesso, tbprevenda.datahora_efetiva ,tbprevenda.origem_prevenda, tbprevenda.prevenda_status, tbresponsavel.cpf, tbentrada.autoriza, tbentrada.datahora_autoriza from tbvinculados inner join tbentrada on tbentrada.id_vinculado = tbvinculados.id_vinculado inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel where ".$buscavariavel." and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.prevenda_status not in (0,9) and tbentrada.previnculo_status not in (0,2) order by tbprevenda.id_prevenda asc, tbvinculados.nome asc";

// echo $sql_buscaentradas;


$pre_buscaentradas = $connPDO->prepare($sql_buscaentradas);
$pre_buscaentradas->execute();

if ($pre_buscaentradas->rowCount() <1) {
    ?>

<h3>Nenhuma reserva encontrada</h3>

<?php    
} else {
    $row_buscaentradas = $pre_buscaentradas->fetchAll();
}
?>


<?php if (isset($row_buscaentradas)) { ?>
<table class="table" id="tabela-entradas">
    <thead>
        <th>Ticket</th>
        <th>Responsável</th>
        <th>CPF</th>
        <th>Participante</th>
        <th>Data/hora entrada</th>
        <!-- <th>Origem da reserva</th> -->
        <th>Ação</th>
    </thead>
    <tbody>
        <?php foreach ($row_buscaentradas as $key => $value) { ?>
        <tr>
            <td><?= $value['id_prevenda'] ?></td>
            <td><?= $value['nomeresponsavel'] ?></td>
            <td><?= formatarCPF($value['cpf']) ?></td>
            <td><?= $value['nomecrianca'] ?></td>
            <td><?= ($value['datahora_efetiva']==""?"-": date('d/m/Y H:i:s', $value['datahora_efetiva'])); ?></td>
            <!-- <td><?= $value['origem_prevenda']==1?"Online":($value['origem_prevenda']==2?"Presencial":"-") ?></td> -->
            <td>
                <?php if ($value['origem_prevenda']==1 && $value['autoriza']==1) { ?>
                    <a target="_blank" href="termo-pdf?t=<?= $value['identrada'] ?>" class="btn btn-primary btn-xs waves-effect" title="Imprimir"><i class="material-icons">print</i></a>
                <?php } else { ?>
                    Balcão
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>
<script>
    $('#tabela-entradas').dataTable();
</script>