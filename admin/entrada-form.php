<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>
<?php include('./inc/funcoes-gerais.php') ?>

<?php 


$evento = $_SESSION['evento_selecionado'];

//procedimento de busca dos pacotes deste evento
$sql_busca_pacote = "select * from tbpacotes where ativo=1 and id_evento=".$evento;
$pre_busca_pacote = $connPDO->prepare($sql_busca_pacote);
$pre_busca_pacote->execute();
$row_busca_pacote = $pre_busca_pacote->fetchAll();

$_SESSION['lista_pacotes'] = $row_busca_pacote;
//----------------------------------------------------------------------------------------------------------------


//procediemnto de busca de tipo de vínculos
$sql_busca_vinculo = "select * from tbvinculo where ativo=1";
$pre_busca_vinculo = $connPDO->prepare($sql_busca_vinculo);
$pre_busca_vinculo->execute();
$row_busca_vinculo = $pre_busca_vinculo->fetchAll();

$_SESSION['lista_vinculos'] = $row_busca_vinculo;
//----------------------------------------------------------------------------------------------------------------

//procedimento de busca dos perfis deste evento
$sql_busca_perfis = "select * from tbperfil_acesso where ativo=1 and idevento=".$evento;
$pre_busca_perfis = $connPDO->prepare($sql_busca_perfis);
$pre_busca_perfis->execute();
$row_busca_perfis = $pre_busca_perfis->fetchAll();

$_SESSION['lista_perfis'] = $row_busca_perfis;
$perfil_padrao = searchInMultidimensionalArray($_SESSION['lista_perfis'], 'padrao_evento', '1');
//----------------------------------------------------------------------------------------------------------------

if ((!isset($_GET['item'])) || (!is_numeric($_GET['item']))) {
    header('Location: entrada-nova.php');
}

$idprevenda = $_GET['item'];

$sql = "SELECT tbresponsavel.*, tbprevenda.id_prevenda, tbprevenda.data_acesso, tbprevenda.datahora_solicita from tbprevenda inner JOIN tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel where tbprevenda.prevenda_status=1 and tbprevenda.id_prevenda=:idprevenda";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();

// die(var_dump($row));
?>


</head>
<body class="theme-black">
<?php include('./inc/pageloader.php') ?>

<?php include('./inc/menu-overlay.php') ?>

<?php include('./inc/menu_topo.php') ?>
<?php include('./inc/menu_principal.php') ?>
<?php include('./inc/menu_lateral.php') ?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Ticket de entrada</h2>                    
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
                <div class="card">
                    <div class="header">
                        <h2>Dados do responsável</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">Adiciona responsável</a></li>
                                    <li><a href="javascript:void(0);">Resp. participante</a></li>
                                    <li><a href="javascript:void(0);">Limpar</a></li>
                                </ul>
                            </li>                            
                        </ul>
                    </div>
                    <div class="body">
                        <form action="" method="post" id="formResponsavel" >
                        
                        <div class="row clearfix">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>                               
                                    <input type="text" class="form-control" placeholder="CPF" value="<?= $row[0]['cpf'] ?>" name="cpf" />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome</label>                            
                                    <input type="text" class="form-control" placeholder="Nome" value="<?= $row[0]['nome'] ?>" name="nome" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 1</label>                            
                                    <input type="text" class="form-control" placeholder="Telefone 1" value="<?= $row[0]['telefone1'] ?>" name="telefone1" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 2</label>                            
                                    <input type="text" class="form-control" placeholder="Telefone 2" value="<?= $row[0]['telefone2'] ?>" name="telefone2" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Email</label>                            
                                    <input type="text" class="form-control" placeholder="Email" value="<?= $row[0]['email'] ?>" required name="email" />
                                </div>
                            </div> 

                            <div class="col-md-6">
                                <div class="form-group js-sweetalert">                                   
                                     <input type="hidden" name="idresponsavel" value="<?= $row[0]['id_responsavel'] ?>">
                                    <button class="btn btn-raised btn-primary waves-effect btn-round" data-type="salvo" type="submit" disabled>Salvar</button>                                
                                </div>
                            </div> 
                            
                        </div>                       
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                <div class="header">
                        <h2>Crianças/participantes adicionadas</h2>
                        <ul class="header-dropdown">
                            <li><a href="#modalAddParticipante" data-toggle="modal" data-target="#modalAddParticipante"><i class="zmdi zmdi-plus-circle"></i></a></li>
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">Item 1</a></li>
                                    <li><a href="javascript:void(0);">Item 2</a></li>
                                    <li><a href="javascript:void(0);">Item 3</a></li>
                                </ul>
                            </li>                            
                        </ul>
                    </div>
                    <div class="card bloco-vinculados">
                    
                    
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
                                <div class="form-group js-sweetalert">
                                    <a class="btn btn-raised btn-primary waves-effect btn-round" href="entrada-nova.php">Retornar</a>                               
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group js-sweetalert">
                                    <button class="btn btn-raised btn-primary waves-effect btn-round" id="btnpagamento" data-numrow="">Efetuar pagamento</button>                               
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>     
    </div>
</section>

<?php include('./inc/entrada-form-modal.php'); ?>

<div class="modal fade" id="modalEditaParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<?php include('./inc/javascript.php'); ?>
<script>
    $(document).ready(function(){
        $('select').selectpicker();

        $('form#formResponsavel').on('input change', function(){
            $('form#formResponsavel button[type=submit]').attr('disabled', false);
        });

        $('form#formResponsavel').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/atualiza-responsavel.php', formAtual.serialize(), function(data){
                swal({
                    title: "Dados salvos",
                    text: "Os dados informados foram salvos com sucesso!" + data,
                    type: "success",
                    showCancelButton: false,
                    closeOnConfirm: true
                }, function () {
                    $('form#formResponsavel button[type=submit]').attr('disabled', true);
                });                
            });
            
        })

        $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> });

        $('body').on('change', '.lista-vinculados select',  function(){
            let entrada = $(this).data('identrada');
            let pacote  = $(this).val();
            // alert( entrada + ' - ' + pacote );
            $.post( "./blocos/troca-pacote.php", { e: entrada, p: pacote }, function(data){
                console.log(data);
            });
        });

        $('body').on('click', '.excluivinculo', function(){
            let entrada = $(this).data('identrada');
            if (confirm('Confirma esta exclusão?')) {
                $.post("./blocos/exclui-vinculo.php", { e: entrada }, function(data){
                    $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> });
                });
            }
        });

        $('body').on('click','#btnpagamento', function(event){
            
            let count = $(this).data('numrow');
            if (count<1) {
                swal({
                    title: "Atenção",
                    text: "Não existem crianças adicionadas para esta reserva.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });
            } else {
                location.href='pagamento.php?item=<?= $_GET['item'] ?>'
            }            
        })
    });
</script>

</body>
</html>