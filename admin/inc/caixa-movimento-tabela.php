<?php
$idcaixaabre = $row_buascacaixa['id'];
$sql_buscamovimento = "SELECT tbcaixa_movimento.*, tbcaixa_tipodespesa.descricao as descricao_tipodespesa FROM tbcaixa_movimento inner join tbcaixa_tipodespesa on tbcaixa_movimento.idtipodespesa=tbcaixa_tipodespesa.id WHERE tbcaixa_movimento.ativo=1 and tbcaixa_movimento.idevento=".$_SESSION['evento_selecionado']." and tbcaixa_movimento.idcaixaabre=".$idcaixaabre." order by tbcaixa_movimento.datahora_insercao asc";
$pre_buscamovimento = $connPDO->prepare($sql_buscamovimento);
$pre_buscamovimento->execute();

if ($pre_buscamovimento->rowCount() > 0) {
    $row_buscamovimento = $pre_buscamovimento->fetchAll();

?>


<table class="table table-hover">
    <thead>
        <tr>    
            <th width="5%">#</th>
            <th width="5%">Item</th>
            <th width="60%">Descrição</th>
            <th width="10%">Valor</th>
            <th width="10%">Tipo despesa</th>
            <th width="10%">Ação</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $soma = 0;
        foreach ($row_buscamovimento as $k => $v) { 

            $soma += $v['valor'];
            
            ?>
            <tr>
                <td><?=  str_pad($k+1, 3, "0", STR_PAD_LEFT);  ?></td>
                <td><?= $v['item'] ?></td>
                <td><?= $v['descricao'] ?></td>
                <td><?= number_format($v['valor'], 2, ',', '.') ?></td>
                <td><?= $v['descricao_tipodespesa'] ?></td>
                <td>
                    <?php if ($row_buascacaixa['status'] == 1) { ?>
                        <a href="#" data-iditem="<?= $v['id'] ?>" class="btn btn-sm btn-danger exluimovimento" title="Excluir"><i class="zmdi zmdi-close"></i></a>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" >Total</th>
                    <th colspan="3">R$ <?= number_format($soma, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
</table>


<?php
} else {
    echo '<h4 class="text-center">Nenhum resultado encontrado</h4>';
}
?>

<script>
    $(document).ready(function() {
        $('.exluimovimento').click(function(e){
            e.preventDefault();
            let iditem = $(this).data('iditem');

            swal({
                title: 'Atenção',
                text: 'Deseja realmente excluir este item?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: true
            }, function(isConfirm){
                if (isConfirm) {
                    
                    $.post('./blocos/caixa-movimento-exclui.php', {item: iditem}, function(data){
                        // console.log(data);
                        window.location.reload();
                    });
                }
            })


            
        });
    });
</script>