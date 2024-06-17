<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
include_once('../inc/conexao.php');
$idprevenda = intval($_POST['i']);

// die($idprevenda);
/*
$sql = "SELECT tbentrada.*, tbprevenda.id_evento,  tbvinculados.nome, tbvinculados.nascimento, tbvinculo.descricao as descricao_vinculo, tbvinculados.tipo as tipovinculo FROM tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbprevenda on tbvinculados.id_responsavel=tbprevenda.id_responsavel
inner join tbvinculo on tbvinculo.id_vinculo=tbvinculados.tipo
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda order by nome ";
*/

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbvinculo.descricao as tipovinculo, tbentrada.id_pacote, tbpacotes.descricao as pacote, tbprevenda.id_evento, tbentrada.id_prevenda, tbpacotes.rotulo_cliente
FROM tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbvinculo on tbvinculados.tipo=tbvinculo.id_vinculo
inner join tbpacotes on tbpacotes.id_pacote=tbentrada.id_pacote
inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
WHERE tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda order by nome";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();
$row = $pre->fetchAll();

$rowNum = $pre->rowCount();

// var_dump($row_busca_pacote);
?>

<div class="body lista-vinculados">
    <div class="table-responsive">
        <?php 
        if ($pre->rowCount()>0) { 

            // $sql_busca_pacote = "select * from tbpacotes where ativo=1 and id_evento=".$row[0]['id_evento'];
            // $pre_busca_pacote = $connPDO->prepare($sql_busca_pacote);
            // $pre_busca_pacote->execute();
            // $row_busca_pacote = $pre_busca_pacote->fetchAll();
            
        ?>
                
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Nascimento</th>
                    <th>Vínculo</th>
                    <th>Pacote</th>
                    <th>Action</th>
                </tr>
            </thead>                                
            <tbody>

            <?php  foreach ($row as $key => $value) { ?>
               
                <tr>
                    <td><?= $row[$key]['id_vinculado'] ?></td>
                    <td><?= $row[$key]['nome'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row[$key]['nascimento'])) ?></td>
                    <td><span class="badge badge-success"><?= $row[$key]['tipovinculo'] ?></span></td>
                    <td>
                        <select class="form-control show-tick p-0" data-identrada="<?= $row[$key]['id_entrada'] ?>">
                            <option value="">Escolha</option>
                            <?php foreach ($_SESSION['lista_pacotes'] as $k => $v) { ?>
                            <option <?= ($v['id_pacote']==$row[$key]['id_pacote']?'selected':'') ?> value="<?= $v['id_pacote'] ?>"><?= $v['descricao'] ?> (<?= $v['rotulo_cliente'] ?>)</option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                    <!-- <a href="#modalAddParticipante" data-toggle="modal" data-target="#modalAddParticipante" class="btn btn-icon btn-neutral btn-icon-mini margin-0"><i class="zmdi zmdi-edit"></i></a> -->
                        <!-- <button data-toggle="modal" data-identrada="<?= $row[$key]['id_entrada'] ?>" data-target="#modalAddParticipante" class="btn btn-icon btn-neutral btn-icon-mini margin-0 btnModalAddParticipante"><i class="zmdi zmdi-edit"></i></button> -->

                        <button data-idprevenda="<?= $row[$key]['id_prevenda'] ?>" data-idparticipante="<?= $row[$key]['id_vinculado'] ?>" class="btn btn-icon btn-neutral btn-icon-mini margin-0 btnModalAddParticipante"><i class="zmdi zmdi-edit"></i></button>
                        
                        <button class="btn btn-icon btn-neutral btn-icon-mini margin-0 excluivinculo" data-identrada="<?= $row[$key]['id_entrada'] ?>"><i class="zmdi zmdi-delete"></i></button>
                    </td>
                </tr>
                
             <?php } ?>   
            </tbody>
        </table>

        <?php } else { ?>

            <div class="alert alert-danger">
                <strong>Opa!</strong> Nenhuma criança foi vinculada a este cadastro.
            </div>

        <?php } ?>
    </div>
</div>

<script>
    document.querySelector('#btnpagamento').dataset.numrow = '<?= $rowNum ?>';

    $(document).ready(function(){
        $('.btnModalAddParticipante').on('click', function(){
            let i = $(this).data('idparticipante');
            let j = $(this).data('idprevenda');
            $('#modalAddParticipante').modal();
            $.post("./blocos/busca-participante.php",{p:i, e:j}, function(data){
                let dados = JSON.parse(data);
                $('#fnome').val(dados[0][1]);
                $('#fnascimento').val(dados[0][2]);
                $('#fvinculo').val(dados[0][3]);
                $('#fpacote').val(dados[0][4]);
                // console.log(data);
            });  
        })
    })
    


</script>