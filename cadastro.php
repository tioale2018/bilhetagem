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

$row               = $_SESSION['row'];
$evento_atual      = $_SESSION['evento_atual'];
$id                = $_SESSION['cpf'];
$dados_responsavel = $_SESSION['dadosResponsavel'];
$idPrevendaAtual   = $_SESSION['idPrevenda'];
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

<div class="overlay_menu">
    <button class="btn btn-primary btn-icon btn-icon-mini btn-round"><i class="zmdi zmdi-close"></i></button>
    <div class="container">        
        <div class="row clearfix">
            <div class="card">
                <div class="body">
                    <div class="input-group m-b-0">                
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-addon">
                            <i class="zmdi zmdi-search"></i>
                        </span>
                    </div>
                </div>
            </div>         
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="social">
                    <a class="icon" href="https://www.facebook.com/thememakkerteam" target="_blank"><i class="zmdi zmdi-facebook"></i></a>
                    <a class="icon" href="https://www.behance.net/thememakker" target="_blank"><i class="zmdi zmdi-behance"></i></a>
                    <a class="icon" href="#"><i class="zmdi zmdi-twitter"></i></a>
                    <a class="icon" href="#"><i class="zmdi zmdi-linkedin"></i></a>                    
                    <p>Coded by WrapTheme<br> Designed by <a href="http://thememakker.com/" target="_blank">thememakker.com</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="overlay"></div><!-- Overlay For Sidebars -->


<?php //include('./inc/menu_topo.php') ?>
<?php //include('./inc/menu_principal.php') ?>
<?php //include('./inc/menu_lateral.php') ?>

<section class="content mt-0">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Pré cadastro</h2>                    
                </div> 
                <div class="col-12">
                    <?= $row[0]['titulo'] ?>
                </div>           
               
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                      
                    </div>
                    <div class="body">
                        <form action="" method="post"  class="js-sweetalert" id="formResponsavel">
                        <h2 class="card-inside-title">Dados do responsável</h2>
                        <?php // (isset($dados_responsavel)?'<div style="color:tomato">Dados localizados</div>':'') ?>
                        <div class="row clearfix">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>                               
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" value="<?= $id ?>" />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome</label>                            
                                    <input name="nome" type="text" class="form-control" placeholder="Nome" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['nome']:'') ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 1</label>                            
                                    <input name="telefone1" type="text" class="form-control" placeholder="Telefone 1" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['telefone1']:'') ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 2</label>                            
                                    <input name="telefone2" type="text" class="form-control" placeholder="Telefone 2" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['telefone2']:'') ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Email</label>                            
                                    <input name="email" type="text" class="form-control" placeholder="Email" required value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['email']:'') ?>" />
                                </div>
                            </div> 

                            <div class="col-md-6">
                                <div class="form-group">                                   
                                     
                                    <button class="btn btn-raised btn-primary waves-effect btn-round btsalvar" type="submit" disabled>Salvar</button>                                
                                </div>
                            </div> 
                            
                        </div>                   
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
           
        </script>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2>Adicionar participantes</h2>
                        <ul class="header-dropdown">
                            <li><a href="#modalAddParticipante" data-toggle="modal" data-target="#modalAddParticipante"><i class="zmdi zmdi-plus-circle"></i></a></li>
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">...</a></li>
                                    <li><a href="javascript:void(0);">...</a></li>
                                </ul>
                            </li>                            
                        </ul>
                    </div>
                    
                    
                    <div class="body">
                        <div class="table-responsive bloco-vinculados">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                
                    <div class="body">
                        
                        <div class="row justify-content-end">
                             <div class="col-md-3">
                                <button type="button" data-id="<?= $idPrevendaAtual ?>" data-acao="1" name="btnCancela" class="btn btn-raised btn-primary waves-effect btn-round btAcao">Cancelar cadastro</button>
                            </div>

                            <div class="col-md-3">
                                <button type="button" data-id="<?= $idPrevendaAtual ?>" data-acao="2" name="btnFinaliza" class="btn btn-raised btn-primary waves-effect btn-round btAcao">Finalizar Cadastro</button>
                            </div>
                            

                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php include('./inc/cad-participante-modal.php') ?>

<?php include('./inc/javascript.php') ?>

<script>
    
    $(document).ready(function(){

        $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i: <?= $idPrevendaAtual ?> });
        
        $('form#formResponsavel').on('input change', function(){
            $('.btsalvar').attr('disabled', false);            
        });

        $('form#formResponsavel').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/atualiza-responsavel.php', formAtual.serialize(), function(data){
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
            
        })

        $('.btAcao').on('click', function(){
            let acao = $(this).data('acao');
            let id = $(this).data('id');

            if (acao==1) {
                var vtitle="Cancelar agendamento?";
                var vtext= "Deseja realmente cancelar e excluir esta reserva?";
                var vconfirmButtonText= "Sim";
                var vcancelButtonText= "Não";
            } else if (acao==2) {
                var vtitle= "Finalizar e enviar reserva";
                var vtext= "Deseja concluir o cadastro e enviar a solicitação de reserva?";
                var vconfirmButtonText= "Sim";
                var vcancelButtonText= "Não";
            }

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
            
                if (acao==1) {
                    $.post('./blocos/reserva-cancela.php', {i:id}, function(data){
                        swal({
                            title: "Operação realizada com sucesso",
                            text: "Mensagem de agradecimento",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false
                        }, function () {
                            location.reload();
                        });
                        
                    });
                } else if (acao==2) {
                    location.href="reserva-resumo.php";
                }
                
            });
        });

        $('#formCancela').submit(function(e){
            if(!confirm('Deseja cancelar o cadastro?')) {
                e.preventDefault();
            }
        })
        
    });
</script>


</body>
</html>