<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
include_once('../inc/conexao.php');
include_once('../inc/funcoes-gerais.php');
$idprevenda = intval($_POST['i']);

// die($idprevenda);
/*
$sql = "SELECT tbentrada.*, tbprevenda.id_evento,  tbvinculados.nome, tbvinculados.nascimento, tbvinculo.descricao as descricao_vinculo, tbvinculados.tipo as tipovinculo FROM tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbprevenda on tbvinculados.id_responsavel=tbprevenda.id_responsavel
inner join tbvinculo on tbvinculo.id_vinculo=tbvinculados.tipo
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda order by nome ";
*/

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbvinculo.descricao as tipovinculo, tbentrada.id_pacote, tbpacotes.descricao as pacote, tbprevenda.id_evento, tbentrada.id_prevenda, tbpacotes.rotulo_cliente, tbperfil_acesso.titulo as perfil, tbperfil_acesso.idperfil
FROM tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbvinculo on tbvinculados.tipo=tbvinculo.id_vinculo
left join tbpacotes on tbpacotes.id_pacote=tbentrada.id_pacote
inner join tbperfil_acesso on tbperfil_acesso.idperfil=tbentrada.perfil_acesso
inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
WHERE tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda order by nome";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();
$row = $pre->fetchAll();

$rowNum = $pre->rowCount();

$travaBtEnvia = 'false';
$textoBtEnvia = 'Efetuar pagamento';
$textoBtErro = 'Verifique pacotes pendentes'
// var_dump($row_busca_pacote);
?>

<div class="body lista-vinculados">
    <div class="table-responsive">
        <?php 
        if ($pre->rowCount()>0) { 
        ?>
                
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th width="20%">Nome</th>
                    <th width="20%">Nascimento / Idade</th>
                    <th width="10%">Vínculo</th>
                    <th width="10%">Perfil</th>
                    <th width="30%">Pacote</th>
                    <th width="10%">Ações</th>
                </tr>
            </thead>                                
            <tbody>

            <?php  foreach ($row as $key => $value) { 
                if ($row[$key]['id_pacote']==0) {
                    $travaBtEnvia = 'true';
                    $textoBtEnvia = $textoBtErro;
                }
                ?>
               
                <tr>
                    <td><?= $row[$key]['nome'] ?></td>
                    <td>
                        <div><?= date('d/m/Y', strtotime($row[$key]['nascimento'])) ?></div>
                        <div>(<?= calcularIdade($row[$key]['nascimento']) ?> Anos)</div>
                    </td>
                    <td><span class="badge badge-success"><?= $row[$key]['tipovinculo'] ?></span></td>
                    <td><?= $row[$key]['perfil'] ?></td>
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

                        <button data-idprevenda="<?= $row[$key]['id_prevenda'] ?>" data-idparticipante="<?= $row[$key]['id_vinculado'] ?>" class="btn btn-icon btn-neutral btn-icon-mini margin-0 btnModalEditaParticipante"><i class="zmdi zmdi-edit"></i></button>
                        
                        <button class="btn btn-icon btn-neutral btn-icon-mini margin-0 excluivinculo" data-identrada="<?= $row[$key]['id_entrada'] ?>"><i class="zmdi zmdi-delete"></i></button>
                    </td>
                </tr>
                
             <?php } ?>   
            </tbody>
        </table>

        <?php 
        } else { 
            $travaBtEnvia = 'true';
            $textoBtEnvia = $textoBtErro;          
            ?>

            <div class="alert alert-danger">
                <strong>Opa!</strong> Nenhuma criança foi vinculada a este cadastro.
            </div>

        <?php } ?>
    </div>
</div>

<script>

$(document).ready(function(){
    document.querySelector('#btnpagamento').dataset.numrow = '<?= $rowNum ?>';
    $('#btnpagamento').prop('disabled', <?= $travaBtEnvia ?>).text('<?= $textoBtEnvia ?>');

        $('.btnModalEditaParticipante').on('click', function(){
            let i = $(this).data('idparticipante');
            let j = $(this).data('idprevenda');
            $('#modalEditaParticipante').modal();

            $('#modalEditaParticipante .modal-content').load('./blocos/edita-participante.php', {i: i, j: j});
        })

        $('select').selectpicker();

        $('body').on('click', '.excluivinculo', function(){
            let entrada = $(this).data('identrada');

            swal({
                    title: "Confirma esta exclusão?",
                    text: "Esta ação não pode ser revertida!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: "Não",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.post("./blocos/exclui-vinculo.php", { e: entrada }, function(data){
                            $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $idprevenda ?> });
                        });
                    } 
                });

          
        });


    });

</script>