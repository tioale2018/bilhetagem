<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes-gerais.php');

if (!isset($_SESSION['evento'])) {
    echo json_encode(['error' => 'session_expired']);
    exit;
}

$datasHoje = geraDatasSQL(date('Y-m-d'));

$sql = "SELECT tbprevenda.id_prevenda, tbresponsavel.id_responsavel, tbresponsavel.nome, tbresponsavel.cpf, tbprevenda.data_acesso, tbprevenda.datahora_solicita, tbprevenda.prevenda_status FROM tbprevenda inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
where tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.prevenda_status=1 and datahora_solicita between ". $datasHoje['start'] ." and ". $datasHoje['end'];
$pre = $connPDO->prepare($sql);
$pre->execute();
$row = $pre->fetchAll();


?>


<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Acesso</th>
                <th>Responsável</th>
                <th>CPF</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>                                
        <tbody>
            <?php 
            
            if ($pre->rowCount()>0) {
            
                foreach ($row as $key => $value) { ?>
                <tr>
                    <td><?= date('d/m/Y', $value['datahora_solicita']) ?></td>
                    <td><?= $value['nome'] ?></td>
                    <td><?= $value['cpf'] ?></td>
                    <td><span class="badge badge-default"><?=  ($value['prevenda_status']==1?'Agendado':'Outro') ?></span></td>
                    <td>
                        <a class="btn btn-icon btn-neutral btn-icon-mini margin-0" href="entrada-form?item=<?=  $value['id_prevenda'] ?>"><i class="zmdi zmdi-sign-in"></i></a>
                    </td>
                </tr>
                <?php } 
            } else {
                ?>
                <tr><td colspan="5" style="text-align: center">Nenhum registro encontrado</td></tr>
            <?php } ?>

        </tbody>
    </table>
</div>