<?php include_once('./inc/head.php') ?>

<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>
<?php include_once('./inc/funcoes.php') ?>

<?php

function generateSqlQuery($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if ($dateTime === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }
    $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();
    $endTimestamp = $dateTime->setTime(23, 59, 59)->getTimestamp();
    $sql = "SELECT tbfinanceiro.*, tbprevenda.id_evento FROM tbfinanceiro inner join tbprevenda on tbfinanceiro.id_prevenda=tbprevenda.id_prevenda WHERE tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbfinanceiro.ativo=1 AND tbfinanceiro.hora_pgto BETWEEN {$startTimestamp} AND {$endTimestamp} order by tbprevenda.id_prevenda desc, tbfinanceiro.hora_pgto desc";
    return $sql;
}

$varget = '';
  
    if (isset($_GET['d']) && isValidDate($_GET['d'])) {
        $dataRelata = $_GET['d'];
        $varget = '?d='.$_GET['d'];
    } else {
        $dataRelata = date('Y-m-d');
    }

    $sql_busca_pgto = generateSqlQuery($dataRelata);
    // die($sql_busca_pgto);
    $pre_busca_pgto = $connPDO->prepare($sql_busca_pgto);
    $pre_busca_pgto->execute();
    $row_busca_pgto = $pre_busca_pgto->fetchAll();

    $total = 0;   
?>

</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12 mt-4">
                    <h2>Informação de pagamentos detalhada</h2>             
                       
                </div>            
                <!-- <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Basic Elements</li>
                    </ul>
                </div> -->
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">  
                    
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0 row">
                                        <div class="col-md-3"><strong>Data:</strong></div> 
                                        <div class="col-md-6"><input class="form-control" type="date" name="" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value="<?= $dataRelata ?>"></div> 
                                </p>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <p>
                                    <a href="caixa-tipopgto<?= $varget ?>">Por tipo de pagamento</a> | <a href="caixa-produtos<?= $varget ?>">Pacotes vendidos</a> | <a href="caixa-basico<?= $varget ?>">Detalhamento</a>
                                </p>
                            </div>
                            <!-- <div class="col-md-6 col-sm-6 text-right">
                            <address>
                                    <strong>{responsável}</strong><br>
                                    {dado1}<br>
                                    {dado2}
                                </address>
                            </div> -->
                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive tabela-caixa">
                                
<table class="table table-hover">
<thead>
    <tr>
                                                                
        <th>Ticket venda</th>
        <th>Cobrança</th>
        <th>Forma pgto</th>
        <th>Hora pgto</th>
        <th>Valor</th>
    </tr>
</thead>
<tbody>
<?php if($pre_busca_pgto->rowCount() < 1) { ?>
        <tr>
            <td colspan="6" style="text-align: center">Nenhum resultado encontrado</td>
        </tr>
<?php } else { 

    foreach ($row_busca_pgto as $key => $value) {
        $total = $total + $value['valor'];
        ?>
    <tr>
            
        <th><?= $value['id_prevenda'] ?></th>
        <th><?= $tpcobranca[$value['tp_cobranca']] ?></th>
        <th><?= $formapgto[$value['forma_pgto']] ?></th>
        <th><?= date('H:i', $value['hora_pgto']) ?></th>
        <th>R$ <?= number_format($value['valor'], 2, ',', '.') ?></th>
    </tr>
        <?php
    }

 } ?>
        </tbody>
    </table>

    <p>Total: R$ <?= number_format($total, 2, ',', '.') ?></p>

                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php include_once('./inc/javascript.php') ?>

<script>
    $(document).ready(function(){
        $('#dataFiltro').change(function(){
            var data = $(this).val();
            window.location = 'caixa-basico.php?d='+data;
        });
    })
</script>

</body>
</html>