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
   // $sql = "SELECT count(id_pacote) as total_vendido, pct_nome, pct_valor, pct_duracao FROM tbentrada where datahora_entra BETWEEN {$startTimestamp} AND {$endTimestamp} group by id_pacote order by total_vendido";

    $sql = "SELECT count(tbentrada.id_pacote) as total_vendido, tbentrada.pct_nome, tbentrada.pct_valor, tbentrada.pct_duracao, tbprevenda.id_evento FROM tbentrada inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda where tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbentrada.datahora_entra BETWEEN  {$startTimestamp} AND {$endTimestamp} group by tbentrada.id_pacote order by total_vendido";
    // $sql = "SELECT * FROM tbfinanceiro WHERE ativo=1 AND hora_pgto BETWEEN ";
    return $sql;
}

    if (isset($_GET['d']) && isValidDate($_GET['d'])) {
        $dataRelata = $_GET['d'];
    } else {
        $dataRelata = date('Y-m-d');
    }

    $sql_busca_pgto = generateSqlQuery($dataRelata);
    $pre_busca_pgto = $connPDO->prepare($sql_busca_pgto);
    $pre_busca_pgto->execute();
    $row_busca_pgto = $pre_busca_pgto->fetchAll();

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
                    <h2>Informação de pagamentos</h2>        
                </div>
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
                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive tabela-caixa">
                <table class="table table-hover">
                    <thead>
                        <tr>                                                  
                            <th>Nome do pacote</th>
                            <th>Valor</th>
                            <th>Duração</th>
                            <th>Qtde. vendida</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($pre_busca_pgto->rowCount() < 1) { ?>
                            <tr>
                                <td colspan="7" style="text-align: center">Nenhum resultado encontrado</td>
                            </tr>
                    <?php } else { 
                        foreach ($row_busca_pgto as $key => $value) {
                            ?>
                        <tr>
                            <th><?= $value['pct_nome'] ?></th>
                            <th><?= number_format($value['pct_valor'], 2, ',', '.') ?></th>       
                            <th><?= $value['pct_duracao'] ?></th>       
                            <th><?= $value['total_vendido'] ?></th>       
                        </tr>
                            <?php
                        }

                    } ?>
                            </tbody>
                </table>

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
            window.location = 'caixa-produtos?d='+data;
        });
    })
</script>

</body>
</html>