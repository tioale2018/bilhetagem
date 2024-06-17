<?php session_start(); ?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>

<?php
if ((!isset($_GET['item'])) || (!is_numeric($_GET['item']))) {
    header('Location: entrada-nova.php');
}

$idprevenda = $_GET['item'];

$sql = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor from tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();

?>

</head>
<body class="theme-black">
<?php include('./inc/pageloader.php') ?>

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

<?php include('./inc/menu_topo.php') ?>
<?php include('./inc/menu_principal.php') ?>
<?php include('./inc/menu_lateral.php') ?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Basic Elements</h2>                    
                </div>            
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Basic Elements</li>
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
                                <address>
                                    <strong>ThemeMakker Inc.</strong><br>
                                    795 Folsom Ave, Suite 546<br>
                                    San Francisco, CA 54656<br>
                                    <abbr title="Phone">P:</abbr> (123) 456-34636
                                </address>
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <p class="m-b-0"><strong>Order Date: </strong> Jun 15, 2018</p>
                                <p class="m-b-0"><strong>Order Status: </strong> <span class="badge badge-warning m-b-0">Pending</span></p>
                                <p><strong>Order ID: </strong> #123456</p>
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
                                                <th>Pacote</th>
                                                <th>Duração</th>
                                                <th>Valor</th>
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
                                                <td><?= $value['valor'] ?></td>
                                            </tr>

                                            <?php }  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 id="anotar">Note</h5>
                            </div>
                        </div>
                        <form action="" method="post" id="formpgto" class="row">
                            
                            <div class="col-md-6">
                                <table class="table m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Forma de pagamento</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Cartão de crédito</td>
                                            <td><input type="text" class="form-control money-dollar" name="pgtocc" placeholder="Ex: 99,99 $"></td>
                                        </tr>
                                        <tr>
                                            <td>Cartão de débito</td>
                                            <td><input type="text" class="form-control money-dollar" name="pgtocd" placeholder="Ex: 99,99 $"></td>
                                        </tr>
                                        <tr>
                                            <td>Dinheiro</td>
                                            <td><input type="text" class="form-control money-dollar" name="pgtodi" placeholder="Ex: 99,99 $"></td>
                                        </tr>
                                        <tr>
                                            <td>Pix</td>
                                            <td><input type="text" class="form-control money-dollar" name="pgtopi" placeholder="Ex: 99,99 $"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 text-right">
                                <p class="m-b-0"><b>Sub-total:</b> <span id="subtotal"><?= $total ?></span></p>
                                <p class="m-b-0">Discout: 12.9%</p>
                                <p class="m-b-0">VAT: 12.9%</p>                                        
                                <h3 class="m-b-0 m-t-10">R$ <?= number_format($total, 2, ',', '.') ?></h3>
                            </div>                                    
                            <div class="hidden-print col-md-12 text-right js-sweetalert">
                                <input type="hidden" name="idprevenda" value="<?= $_GET['item'] ?>">
                                <input type="hidden" name="" value="">
                                <input type="hidden" name="" value="">
                                <hr>
                                <button class="btn btn-warning btn-icon  btn-icon-mini btn-round"><i class="zmdi zmdi-print"></i></button>
                                <button class="btn btn-raised btn-primary btn-round" type="submit">Efetuar pagamento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php include('./inc/javascript.php') ?>

<script>
    $(document).ready(function() {
        var subtotalElement = $('#subtotal');
        var subtotalInicial = parseFloat(subtotalElement.text());
        var inputs = $('#formpgto input');
        function calcularSubtotal() {
            var subtotal = subtotalInicial; 
            inputs.each(function() {
                var valor = $(this).val().trim();
                if (valor !== '') {
                    subtotal -= parseFloat(valor.replace(',', '.'));
                }
            });

            subtotalElement.text(subtotal.toFixed(2));
        }
        inputs.on('input', function() {
            calcularSubtotal();
        });

        
    });


    

</script>


<script>
    /*
    $(document).ready(function() {
        // Função para verificar se pelo menos um campo foi preenchido
        function verificarCamposPreenchidos() {
            var camposPreenchidos = false;
            $('.money-dollar').each(function() {
                if ($(this).val().trim() !== '') {
                    camposPreenchidos = true;
                    return false; // Para o loop quando um campo for encontrado preenchido
                }
            });
            return camposPreenchidos;
        }

        // Adiciona um evento de clique ao botão de submit
        $('#formpgto').on('submit', function(event) {
            if (!verificarCamposPreenchidos()) {
                
                swal({
                    title: "Atenção",
                    text: "Preencha pelo menos um dos campos antes de prosseguir.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });
                event.preventDefault(); // Impede o envio do formulário
            }
        });
    });
    */
</script>


<script>
    $(document).ready(function() {
        // Seleciona o elemento do subtotal e obtém o valor inicial
        var subtotalInicial = parseFloat($('#subtotal').text());

        // Função para calcular o total dos campos preenchidos
        function calcularTotalCampos() {
            var total = 0;
            $('.money-dollar').each(function() {
                var valor = $(this).val().trim();
                if (valor !== '') {
                    total += parseFloat(valor.replace(',', '.'));
                }
            });
            return total;
        }

        // Adiciona um evento de clique ao botão de submit
        $('#formpgto').on('submit', function(event) {
            event.preventDefault(); // Impede o envio do formulário
            let totalCampos = calcularTotalCampos();
            let formAtual = $(this);

            if (totalCampos === 0) {
                swal({
                    title: "Atenção",
                    text: "Preencha pelo menos um dos campos antes de prosseguir.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });
                
            } else if (totalCampos !== subtotalInicial) {
                swal({
                    title: "Atenção",
                    text: "A soma dos valores dos campos deve ser igual ao valor total inicial.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });
                //event.preventDefault(); // Impede o envio do formulário
            } else {

                swal({
                    title: "Deseja efetuar este pagamento",
                    text: "Sub texto desta operação",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, efetuar pagamento",
                    cancelButtonText: "Não, cancelar e retornar",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.post('./blocos/efetua-pagamento.php', formAtual.serialize(), function(data){
                            location.href="entrada-nova.php";
                        });
                       //alert('OK');
                       
                    } 
                });
                
            }
        });
    });
</script>


</body>
</html>