<?php 
/*
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('Location: index.php');
}
*/
session_start();

if (!isset($_SESSION['dadosResponsavel'])) {
    header('Location: index.php');
}
//include_once("./inc/cad-participantes-regras.php");
include('./inc/conexao.php');

// $row               = $_SESSION['row'];
$evento_atual      = $_SESSION['evento_atual'];
$id                = $_SESSION['cpf'];
$dados_responsavel = $_SESSION['dadosResponsavel'];
$idPrevendaAtual   = $_SESSION['idPrevenda'];

/*
$sql = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor from tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda";
*/

$sql = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor, tbprevenda.id_responsavel, tbprevenda.id_evento, tbprevenda.prevenda_status, tbresponsavel.nome as nomeresponsavel, tbresponsavel.cpf, tbresponsavel.telefone1, tbresponsavel.telefone2
from tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
where tbentrada.previnculo_status=1 and tbprevenda.prevenda_status=9 and tbentrada.id_prevenda=:idprevenda";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();

$horaAgora = time();


?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php

include_once("./inc/head.php");



?>

</head>
<body class="theme-black">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="assets/images/logo.svg" width="48" height="48" alt="Alpino"></div>
        <p>Aguarde</p>        
    </div>
</div>


<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Pagamento na entrada</h2>
                </div>            
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{Página 01}</a></li>
                        <li class="breadcrumb-item active">{Página atual}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">                                
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0"><strong>Data: </strong> <?= date('d/m/Y H:i', $horaAgora) ?></p>
                                <p class="m-b-0"><strong>Status: </strong> <span class="badge badge-warning m-b-0">Aguardando pagamento</span></p>
                                <p><strong>Ticket ID: </strong> #123456</p>
                                
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                            <address>
                                    <strong><?= $row['0']['nomeresponsavel'] ?></strong><br>
                                    <?= $row['0']['telefone1'] ?><br>
                                    {dado2}
                                </address>
                            </div>
                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>                                                        
                                                <th>Participante</th>                                                        
                                                <th>Perfil</th>
                                                <th>Duração</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            foreach ($row as $key => $value) { 
                                                $total = $total + $value['valor'];
                                                ?>
                                                
                                            <tr>
                                                <td><?= $key + 1 ?></td>                                                        
                                                <td><?= $value['nome'] ?></td>
                                                <td><?= $value['descricao'] ?></td>
                                                <td><?= $value['duracao'].'min' ?></td>
                                                
                                            </tr>

                                            <?php }  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <form action="" method="post" id="formFinalizaReserva" class="row">
                            
                                                   
                            <div class="hidden-print col-md-12 text-right">
                                <div class="row">
                                    
                                    <div class="col-3 offset-md-6">
                                        <button class="btn btn-raised btn-primary btn-round btCancela" type="button">Cancelar reserva</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-raised btn-primary btn-round" type="submit">Confirmar reserva</button>
                                    </div>
                                </div>

                                <input type="hidden" name="valorpgto" value="<?= $total ?>">
                                <input type="hidden" name="idprevenda" value="<?= $idPrevendaAtual ?>">
                                <input type="hidden" name="horavenda" value="<?= $horaAgora ?>">
                                <hr>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php //include('./inc/menu_topo.php') ?>
<?php //include('./inc/menu_principal.php') ?>
<?php //include('./inc/menu_lateral.php') ?>


<?php //include('./inc/cad-participante-modal.php') ?>

<?php include('./inc/javascript.php') ?>

<script>

    $(document).ready(function(){
        

        $('#formFinalizaReserva').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            let vtitle= "Finalizar e enviar reserva";
            let vtext= "Deseja concluir o cadastro e enviar a solicitação de reserva?";
            let vconfirmButtonText= "Sim";
            let vcancelButtonText= "Não";
            


            swal({
                title: vtitle,
                text: vtext,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: vconfirmButtonText,
                cancelButtonText: vcancelButtonText,
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    $.post('./blocos/reserva-finaliza.php', formAtual.serialize(), function(data){
                        console.log(data);
                        swal({
                            title: "Operação realizada com sucesso",
                            text: "Mensagem de agradecimento",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false
                        }, function () {
                            //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            location.reload();
                        });
                        
                    });
                } 
            });

           
        });

         /*
            $.post('./blocos/reserva-finaliza.php', formAtual.serialize(), function(data){
                console.log(data);
                swal({
                    title: "Dados salvos",
                    text: "Os dados informados foram salvos com sucesso!",
                    type: "success",
                    showCancelButton: false,
                    closeOnConfirm: true
                }, function () {
                    $('.btsalvar').attr('disabled', true);
                });                
            });
            */
            
            
      

        $('.btCancela').on('click', function(){
            let vtitle="Cancelar agendamento?";
            let vtext= "Deseja realmente cancelar e excluir esta reserva?";
            let vconfirmButtonText= "Sim";
            let vcancelButtonText= "Não";
            let id = $(this).data('id');

            swal({
                title: vtitle,
                text: vtext,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: vconfirmButtonText,
                cancelButtonText: vcancelButtonText,
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    $.post('./blocos/reserva-cancela.php', {i:id}, function(data){
                        console.log(data);
                        swal({
                            title: "Operação realizada com sucesso",
                            text: "Mensagem de agradecimento",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false
                        }, function () {
                            //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            location.reload();
                        });
                        
                    });
                } 
            });
            
        });

        


        
        
    });
</script>




</body>
</html>