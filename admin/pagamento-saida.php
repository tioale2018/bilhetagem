<?php include_once('./inc/head.php') ?>
<?php 
if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header('Location: ./controle.php');
}

if ( !isset($_POST['tipopgto']) ) {
    header('Location: controle.php');
}
?>

<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>

<?php
if (!isset($_POST['chkvinculado'])) {
    header('Location: controle.php');
}

$chkvinculados = $_POST['chkvinculado'];

$lst_vinculos  = implode(',', $_POST['chkvinculado']);
$idprevenda    = $_POST['idprevenda'];
$tipopgto      = $_POST['tipopgto'];
$hora_finaliza = $_POST['tempo_agora'];

$sql = "select tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbresponsavel.telefone1, tbresponsavel.telefone2, tbpacotes.min_adicional as adicionalpacote, '$hora_finaliza' as datahora_saida
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3 and tbentrada.id_prevenda=:idprevenda and tbentrada.id_vinculado in ($lst_vinculos)
order by tbentrada.datahora_entra";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();

$rows = $pre->fetchAll(PDO::FETCH_ASSOC);

$resultadoFinal = [];

foreach ($rows as $row) {
    $horaEntrada = $row['datahora_entra'];
    $horaSaida = $hora_finaliza;
    $pacote = $row['duracao'];
    $tolerancia = $row['tolerancia'];

    $calculos = calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia);

    // Combina os dados originais com os cálculos
    $resultadoFinal[] = array_merge($row, $calculos);
}

?>

<hr>

</head>
<body class="theme-black">
<?php include_once('./inc/pageloader.php') ?>

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

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Pagamento Saída</h2>                    
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
                                <p class="m-b-0"><strong>Data: </strong> 01/01/2000 18:03</p>
                                <p class="m-b-0"><strong>Status: </strong> <span class="badge badge-warning m-b-0">Aguardando pagamento</span></p>
                                <p><strong>Ticket ID: </strong> #123456</p>
                                
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                            <address>
                                    <strong>{responsável}</strong><br>
                                    {dado1}<br>
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
        <th>H. Entrada</th>
        <th>H. Saída</th>
        <th>Pacote</th>
        <th>Permanencia</th>
        <!-- <th>Excedeu</th> -->
        <th>Valor</th>
    </tr>
</thead>
<tbody>
    <?php 
            $total = 0;
            $financeiro_detalha = array();
            foreach ($resultadoFinal as $key => $value) { 
                // $total = $total + ($value['adicionalpacote'] * $value['tempoExcedenteMinutos']);
                $tempoPermanece = calcularPermanenciaEmMinutos($value['datahora_entra'], $value['datahora_saida']);
                $apagar = $value['adicionalpacote'] * calcularExcedente($value['duracao'], $value['tolerancia'], $tempoPermanece);
                $total = $total + $apagar;
                $financeiro_detalha['identrada'][] = $value['id_entrada'];
                $financeiro_detalha['nome'][] = $value['nome'];
                $financeiro_detalha['datahora_saida'][] = $value['datahora_saida'];
                $financeiro_detalha['duracao'][] = $value['duracao'];
                $financeiro_detalha['tempoPermanece'][] = $tempoPermanece;
                $financeiro_detalha['apagar'][] = $apagar;

                $financeiro_detalha['pgtoinout'][] = 2;
    ?>
            <tr>
                <td><?= $key + 1 ?></td>                                                        
                <td><?= $value['nome'] ?></td>
                <td><?= date('H:i', $value['datahora_entra']) ?></td>
                <td><?= date('H:i',$hora_finaliza) ?></td>
                <td><?= $value['duracao'].'min' ?></td>
                <td><?= $tempoPermanece . "min" ?></td>
                <!-- <td><?= calcularExcedente($value['duracao'], $value['tolerancia'], $tempoPermanece) ?></td> -->
                <td> R$ <?= number_format($apagar,2,",",".")  ?></td>
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
                                <h5 id="anotar">Informação de pagamento</h5>
                            </div>
                        </div>
                        <form action="" method="post" id="formpgto" class="row">
                            
                            <div class="col-md-6">
                                <table class="table m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Valor a pagar</th>
                                            <th>Forma de pagamento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-weight: bold">R$ <?= number_format($total, 2, ',', '.') ?></td>
                                            <td>
                                            <select class="form-control show-tick p-0" name="tipopgto" id="ftipopgto" required>
                                                <option value="">Escolha</option>
                                                <option value="1">Cartão de crédito</option>
                                                <option value="2">Cartão de débito</option>
                                                <option value="3">Dinheiro</option>
                                                <option value="4">Pix</option>
                                                <option value="5">Misto</option>
                                            </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="hidden-print col-md-12 text-right js-sweetalert">
                                <input type="hidden" name="idprevenda" value="<?= $idprevenda ?>">
                                <input type="hidden" name="pagasaida" value="true">
                                <input type="hidden" name="pgto" value="<?= $total ?>">
                                <input type="hidden" name="vinculados" value="<?= $lst_vinculos ?>">
                                <input type="hidden" name="horafinaliza" value="<?= $hora_finaliza ?>">
                                <?php
                                    $financeiro_detalha_json = json_encode($financeiro_detalha);
                                ?>
                                <input type="hidden" name="pgtodetalha" value='<?= htmlspecialchars($financeiro_detalha_json, ENT_QUOTES, 'UTF-8') ?>'>
                                <hr>
                                <!-- <button class="btn btn-warning btn-icon  btn-icon-mini btn-round"><i class="zmdi zmdi-print"></i></button> -->
                                <button class="btn btn-raised btn-primary btn-round" type="submit">Efetuar pagamento</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
<pre>
<?php 
// echo var_dump($financeiro_detalha);
include_once('./inc/javascript.php') 
?>
</pre>
<script>

$(document).ready(function(){
    $('#formpgto').submit(function(e){
        let formAtual = $(this);
        e.preventDefault();

        // let subtotal = $('#subtotal').html().replace(',','.');
        // let valor = $('#pgto').val();
        
        // if(subtotal!=valor) {
        //     swal("Erro", "Valor informado não confere com o valor total devido", "error");
        // } else {
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
                            swal({
                                  title: "Concluído", 
                                  text: data + " Pagamento efetuado com sucesso!",
                                  showCancelButton: false,
                                  type: "success"
                                }, function(){
                                location.href="controle.php";
                            })
                           
                           
                           //console.log(data);
                        });
                    } 
                });
        //}

    });
});
    
</script>


</body>
</html>