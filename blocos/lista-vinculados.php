<?php
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}

session_start();

include('../inc/conexao.php');
include('../inc/funcoes.php');
$idprevenda = intval($_POST['i']);

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbvinculo.descricao as tipovinculo, tbvinculados.lembrar, tbentrada.id_pacote, tbperfil_acesso.titulo as perfil, tbprevenda.id_evento, tbentrada.id_prevenda,  tbentrada.autoriza
FROM tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbvinculo on tbvinculados.tipo=tbvinculo.id_vinculo
inner join tbperfil_acesso on tbperfil_acesso.idperfil=tbentrada.perfil_acesso
inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
WHERE tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda order by nome";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();
$row = $pre->fetchAll();

$rowNum = $pre->rowCount();


// Exemplo de uso
// $dataNascimento = "1990-07-15";
// echo "Idade: " . calcularIdade($dataNascimento) . " anos";
?>

<div class="body lista-vinculados">
    <div class="table-responsive">
        <?php 
        $travaBtEnvia = 'false';
        $textoBtEnvia = 'Finalizar pré-cadastro';
        $textoBtErro = 'Autorizações pendentes';
        if ($pre->rowCount()>0) { 
        ?>
                
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <!-- <th>Nascimento</th> 
                    <th>Vínculo</th>
                    <th>Lembrar</th>
                    <th>Perfil</th> -->
                    <th>Autorizar</th>
                    <th>Edita/Exclui</th>
                </tr>
            </thead>                                
            <tbody>

            <?php  
            foreach ($row as $key => $value) { ?>
               
                <tr>
                    <td>
                        <div><?= $row[$key]['nome'] ?></div>
                        <div><?= calcularIdade($row[$key]['nascimento']) ?> Anos</div>
                    </td>
                    <!-- <td><?= date('d/m/Y', strtotime($row[$key]['nascimento'])) ?></td> -->
                    <!-- <td><span class="badge badge-success"><?= $row[$key]['tipovinculo'] ?></span></td>
                    <td><span class="badge badge-success"><?= ($row[$key]['lembrar']==1?'Sim':'Não') ?></span></td> -->
                    <!-- <td><?= $row[$key]['perfil'] ?></td> -->
                    <td class="text-center">
                        <?php 
                            if ($row[$key]['autoriza']==0) {
                                $travaBtEnvia = 'true';
                                $textoBtEnvia = $textoBtErro;
                                echo '<a href="#" class="btnModalTermoParticipante" data-id="'.$row[$key]['id_entrada'].'"><span class="badge badge-danger">Autorizar</span></a>';
                            } else {
                                echo '<span class="badge badge-success">Autorizado</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <button data-idprevenda="<?= $row[$key]['id_prevenda'] ?>" data-idparticipante="<?= $row[$key]['id_vinculado'] ?>" class="btn btn-icon btn-neutral btn-icon-mini margin-0 btnModalEditaParticipante"><i class="zmdi zmdi-edit"></i></button>
                        <button data-entrada="<?= $row[$key]['id_entrada'] ?>" class="btn btn-icon btn-neutral btn-icon-mini margin-0 excluivinculo"><i class="zmdi zmdi-delete"></i></button>
                    </td>
                </tr>
                
             <?php } ?>   
            </tbody>
        </table>

        <?php } else { 
            $travaBtEnvia = 'true';
            $textoBtEnvia = $textoBtErro;
            ?>

            <div class="alert alert-danger">
                <strong>Opa!</strong> Nenhuma criança foi vinculada a este cadastro.
            </div>

        <?php } ?>
    </div>
</div>

<?php if (isset($key)) { ?>

<script>
    $(document).ready(function(){

        $('body').on('click', '.btnModalEditaParticipante', function() {
            let i = $(this).data('idparticipante');
            let j = $(this).data('idprevenda');
            
            $('#modalEditaParticipante').modal();
            $('#formParticipanteContent').load('./blocos/edita-participante.php', {p:i, e:j});
        });

        $('body').on('click', '.btnModalTermoParticipante', function(e) {
            e.preventDefault();
            let i = $(this).data('id');
            
            $('#modalTermoParticipante').modal();
            $('#formTermoParticipante').load('./blocos/participante-termo-modal.php', {i:i});
        });

        $('.excluivinculo').on('click', function(){
            let p = $(this).data('entrada');
            
            swal({
                title: 'Exclusão',
                text: 'Deseja realmente excluir este participante?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#27ae60",
                cancelButtonColor: "#DD6B55",
                closeButtonColor: "#DD6B55",
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post('./blocos/participante-exclui.php', {i:p}, function(data){
                        swal({
                            title: "Operação realizada com sucesso",
                            text: "Mensagem de agradecimento",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                            closeOnConfirm: true
                        }, function () {
                            $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $row[$key]['id_prevenda'] ?> });
                        });
                    });
                }

            });
        });

    });   
</script>

<?php } ?>

<script>
    $('select').selectpicker();
    $('button[name=btnFinaliza]').prop('disabled', <?= $travaBtEnvia ?>).text('<?= $textoBtEnvia ?>');
</script>