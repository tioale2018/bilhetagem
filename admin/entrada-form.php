<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>
<?php include('./inc/funcoes-gerais.php') ?>
<?php include('./inc/funcoes.php') ?>

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
    header('Location: entrada-nova');
}

$idprevenda = $_GET['item'];

$sql = "SELECT tbresponsavel.*, tbprevenda.id_prevenda, tbprevenda.data_acesso, tbprevenda.datahora_solicita, tbprevenda.origem_prevenda, tbprevenda.prevenda_status from tbprevenda inner JOIN tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel where tbprevenda.prevenda_status=1 and tbprevenda.id_prevenda=:idprevenda";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

$pre->execute();

if ($pre->rowCount()<1) {
    header('Location: controle');
}

$row = $pre->fetchAll();

// die(var_dump($row));
?>

<style>
.invalid {
    border: 2px solid red;
}
.page-block {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.4);
    z-index: 99999;
    display: none;
}
</style>

</head>
<body class="theme-black">
<div class="page-block"></div>    
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
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Dados do responsável</h2>
                        <!-- <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">Adiciona responsável</a></li>
                                    <li><a href="javascript:void(0);">Resp. participante</a></li>
                                    <li><a href="javascript:void(0);">Limpar</a></li>
                                </ul>
                            </li>                            
                        </ul> -->
                    </div>
                    <div class="body">
                        <form action="" method="post" id="formResponsavel" >
                        
                        <div class="row clearfix">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>                               
                                    <input type="text" class="form-control" placeholder="CPF" value="<?= $row[0]['cpf'] ?>" name="cpf" readonly />
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
                            <!-- <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">Item 1</a></li>
                                    <li><a href="javascript:void(0);">Item 2</a></li>
                                    <li><a href="javascript:void(0);">Item 3</a></li>
                                </ul>
                            </li>     -->
                        </ul>
                    </div>
                    <div class="">
                    <h6>Ticket: #<?= $_GET['item'] ?></h6>
                    </div>
                    <div class="card bloco-vinculados" style="height: 300px">
                    </div>
                </div>
            </div>
        </div> 

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="row justify-content-end">
                        <div class="col-md-6">
                                <div class="form-group">
                                <?php if ($row[0]['origem_prevenda'] ==2) { ?>
                                    <a class="btn btn-raised btn-danger waves-effect btn-round prevenda-exclui" href="">Excluir reserva</a>    
                                <?php } ?>                           
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group js-sweetalert">
                                    <a class="btn btn-raised btn-primary waves-effect btn-round" href="entrada-nova">Retornar</a>                               
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

        $('body').on('change', '.lista-vinculados select',  function(e){
            // $(this).attr('disabled', true);
            let entrada = $(this).data('identrada');
            let pacote  = $(this).val();
            if (pacote==='') {
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> });
            } else {
                $.post( "./blocos/troca-pacote.php", { e: entrada, p: pacote }, function(data){
                    $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> });
                });    
            }            
        });

        $('body').on('click','#btnpagamento', function(event){
            let botao = $(this);
            botao.attr('disabled', true);
            $('.page-block').css('display', 'flex');

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
                botao.attr('disabled', false);
            } else {
                location.href='pagamento.php?item=<?= $_GET['item'] ?>';
            }

        });
        <?php if ( $row[0]['origem_prevenda'] ==2) { ?>
        $('body').on('click', '.prevenda-exclui', function(e){
            e.preventDefault();

            swal({
                    title: "Confirma esta exclusão?",
                    text: "Esta ação não pode ser revertida!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: "Não",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        
                        $.post("./blocos/prevenda-exclui.php", { i: <?= $_GET['item'] ?> }, function(data){
                            window.location.href = 'controle.php';
                        });
                        
                    } 
                });
        });
        <?php } ?>

        $('select').selectpicker();
    });
</script>


<script>
    $(document).ready(function() {
    // Função para aplicar a máscara de CPF
    function aplicarMascaraCPF(cpf) {
        return cpf
            .replace(/\D/g, '') // Remove caracteres não numéricos
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    // Validação do CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, ''); // Remove caracteres não numéricos
        
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false; // Verifica se o CPF tem 11 dígitos e não é uma sequência de números iguais
        }

        let soma = 0;
        let resto;

        for (let i = 1; i <= 9; i++) {
            soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }
        if (resto !== parseInt(cpf.charAt(9))) {
            return false;
        }

        soma = 0;
        for (let i = 1; i <= 10; i++) {
            soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }
        return resto === parseInt(cpf.charAt(10));
    }

    // Máscara e validação do CPF no campo de entrada
    $('input[name="cpf"]').on('input', function() {
        let cpf = $(this).val();
        $(this).val(aplicarMascaraCPF(cpf));
        
        // Validação do CPF
        if (!validarCPF(cpf.replace(/\D/g, ''))) {
            $(this).css('border', '2px solid red'); // Borda vermelha se o CPF for inválido
            $('button[type="submit"]').prop('disabled', true); // Impede o submit
        } else {
            $(this).css('border', ''); // Reseta a borda
            $('button[type="submit"]').prop('disabled', false); // Permite o submit
        }
    });

    // Reseta a borda ao corrigir o CPF
    $('input[name="cpf"]').on('focus', function() {
        $(this).css('border', '');
        let cpf = $(this).val().replace(/\D/g, '');
        $(this).val(aplicarMascaraCPF(cpf));
    });

    // Remove a máscara ao perder o foco
    $('input[name="cpf"]').on('blur', function() {
        let cpf = $(this).val().replace(/\D/g, '');
        $(this).val(cpf);
    });

    $('input[name="telefone1"]').mask('(00) 0000-00000', {
        onKeyPress: function(val, e, field, options) {
            var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
            $('input[name=telefone1]').mask(mask, options);
        }
    });
    $('input[name="telefone2"]').mask('(00) 0000-00000', {
        onKeyPress: function(val, e, field, options) {
            var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
            $('input[name=telefone2]').mask(mask, options);
        }
    });
});
</script>

</body>
</html>