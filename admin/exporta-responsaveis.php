<?php include_once('./inc/head.php') ?>
<?php include_once('./inc/conexao.php') ?>

<?php include_once('./inc/verifica-permissao.php') ?>

<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>
<?php include_once('./inc/funcoes.php') ?>

<?php
function generateSqlQuery($dataI, $dataF) {
    $dateInicial = DateTime::createFromFormat('Y-m-d', $dataI);
    $dateFinal = DateTime::createFromFormat('Y-m-d', $dataF);

    if ($dateInicial === false || $dateFinal === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }
    $startTimestamp = $dateInicial->setTime(0, 0)->getTimestamp();
    $endTimestamp = $dateFinal->setTime(23, 59, 59)->getTimestamp();
   // $sql = "SELECT count(id_pacote) as total_vendido, pct_nome, pct_valor, pct_duracao FROM tbentrada where datahora_entra BETWEEN {$startTimestamp} AND {$endTimestamp} group by id_pacote order by total_vendido";
    /*
    $sql = "SELECT count(tbentrada.id_pacote) as total_vendido, tbentrada.pct_nome, tbentrada.pct_valor, tbentrada.pct_duracao, tbprevenda.id_evento FROM tbentrada inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda where tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbentrada.datahora_entra BETWEEN  {$startTimestamp} AND {$endTimestamp} group by tbentrada.id_pacote order by total_vendido";
    */
    // $sql = "SELECT * FROM tbfinanceiro WHERE ativo=1 AND hora_pgto BETWEEN ";
/*
    $sql = "SELECT sum(valor) as valor, forma_pgto, hora_pgto, tbprevenda.id_evento
    FROM tbfinanceiro
    inner join tbprevenda on tbprevenda.id_prevenda=tbfinanceiro.id_prevenda
    where tbfinanceiro.forma_pgto>0 and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbfinanceiro.ativo=1 and tbfinanceiro.hora_pgto BETWEEN {$startTimestamp} AND {$endTimestamp} GROUP by tbfinanceiro.forma_pgto";
*/
    $sql = "SELECT tbprevenda.id_responsavel, max(tbprevenda.datahora_efetiva) as ultimavisita, count(tbprevenda.id_responsavel) as visitas, tbresponsavel.nome, tbresponsavel.cpf, tbresponsavel.email, tbresponsavel.telefone1, tbresponsavel.telefone2, tbresponsavel.nascimento
    FROM tbprevenda
    inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
    WHERE tbprevenda.id_evento = ".$_SESSION['evento_selecionado']. " and tbprevenda.datahora_efetiva BETWEEN {$startTimestamp} AND {$endTimestamp} group by id_responsavel order by tbresponsavel.nome";

    return $sql;
}
$varget = '';

$inicialData = date('Y-m-d');
$finalData   = date('Y-m-d');
  
if (isset($_POST['di'])) {
    //validar data
    $inicialData = htmlspecialchars($_POST['di'], ENT_QUOTES, 'utf-8');
    $finalData = htmlspecialchars($_POST['df'], ENT_QUOTES, 'utf-8');
} else {
    if (isset($_GET['di'])) {
        //validar data
        $inicialData = htmlspecialchars($_GET['di'], ENT_QUOTES, 'utf-8');
        $finalData = htmlspecialchars($_GET['df'], ENT_QUOTES, 'utf-8');
    }         
}

$dataRelataIni = $inicialData;
$dataRelataFim = $finalData;

$varget = '?di='.$dataRelataIni.'&df='.$dataRelataFim;

    $sql_busca_pgto = generateSqlQuery($inicialData, $finalData);
    // die("<pre>".$sql_busca_pgto."</pre>");
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
                <div class="col-12 mt-4">
                    <h2>Exportar dados de responsáveis</h2>        
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">  
                        <form action="" method="post">
                            <div class="row">
                                
                                <div class="col-md-7 col-sm-6">
                                    <div class="my-2 row">
                                        <div class="col-md-2"><strong>Data Inicial:</strong></div> 
                                        <div class="col-md-6"><input class="form-control" type="date" name="di" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value="<?= htmlspecialchars($dataRelataIni) ?>"></div> 
                                    </div>
                                    <div class="my-2 row">
                                            <div class="col-md-2"><strong>Data Final:</strong></div> 
                                            <div class="col-md-6"><input class="form-control" type="date" name="df" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value="<?= htmlspecialchars($dataRelataFim) ?>"></div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p style="text-align: right"><button type="submit" class="btn btn-primary">Ok</button></p>

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive tabela-caixa">
                        <table class="table table-hover tabela-exporta-responsaveis">
                            <thead>
                                <tr>                                                  
                                    <th width="30%">Nome</th>
                                    <th width="15%">CPF</th>
                                    <th width="15%">E-mail</th>
                                    <th width="25%">Telefone</th>
                                    <th width="10%">Visitas no período</th>
                                    <th width="5%">Última visita</th>
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
                                    <th><?= htmlspecialchars($value['nome']) ?></th>
                                    <th><?= formatarCPF($value['cpf']) ?></th>
                                    <th><?= htmlspecialchars($value['email']) ?></th>
                                    <th><?= htmlspecialchars($value['telefone1'], ENT_QUOTES) . ($value['telefone2']!=''?" / ".htmlspecialchars($value['telefone2'], ENT_QUOTES):"")?></th>
                                    <th><?= htmlspecialchars($value['visitas']) ?></th>
                                    <th><?= htmlspecialchars(date('d/m/Y', $value['ultimavisita'])) ?></th>      
                                </tr>
                                    <?php
                                }

                            } ?>
                            </tbody>
                        </table>
                <?php if (isset($total)) { ?>
                
                <p style="font-weight: bold">Total: R$ <?= number_format($total, 2, ',', '.') ?></p>
                <?php } ?>

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
        $('form').submit(function(e){
            let di = $('input[name="di"]').val();
            let df = $('input[name="df"]').val();
            if (di>df) {
                e.preventDefault();
                alert('Data inicial deve ser inferior ou igual a data final');
            }
        });

        $('.tabela-exporta-responsaveis').DataTable({
            buttons: [
              {
                extend: "excel",
                className: "btn-sm"
              },
              {
                extend: "pdfHtml5",
                className: "btn-sm"
              },
              {
                extend: "print",
                className: "btn-sm"
              }
            ]
        });

    });
</script>

</body>
</html>