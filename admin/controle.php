<?php 
include_once('./inc/head.php');
include_once('./inc/conexao.php');
include_once('./inc/funcoes-gerais.php');
?>

<style>
    .expired {
        background-color: #f7a8ad;
    }

    .expired:hover {
        background-color: #C26765!important;
        color: #fff!important;
    }

    .expired:hover i{
        color: #fff!important;
    }

    .loader-aguarde {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0 , 0, 0.5);
        display: none;
        z-index: 9999999999;
    }
</style>

<?php
/*
$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbpacotes.descricao as nomepacote
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3  and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." order by tbentrada.datahora_entra";
$pre = $connPDO->prepare($sql);

$pre->execute();
$row = $pre->fetchAll();
*/
?>
</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu-overlay.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<!-- Main Content -->
<section class="content">
    <div class="container">
        <div class="block-header">
            <div class="row clearfix mt-3">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h2 class="">Controle de recreação: <?= $_SESSION['evento_titulo'] ?></h2>                    
                </div>            
            </div>
        </div>
       
        <?php //include_once('./inc/cards-dashboard.php') ?>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card" id="controle-lista">
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php
/*
function geraSQL($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if ($dateTime === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }
    $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();
    $endTimestamp = $dateTime->setTime(23, 59, 59)->getTimestamp();
    // $sql = "SELECT tbfinanceiro.*, tbprevenda.id_evento FROM tbfinanceiro inner join tbprevenda on tbfinanceiro.id_prevenda=tbprevenda.id_prevenda WHERE tbfinanceiro.ativo=1 AND tbfinanceiro.hora_pgto BETWEEN {$startTimestamp} AND {$endTimestamp}";
    $sql = "SELECT * FROM tbprevenda where datahora_efetiva BETWEEN {$startTimestamp} AND {$endTimestamp}";
    return $sql;
}

echo geraSQL('2024-08-01');
*/



?>




<?php include_once('./inc/controle-modal.php') ?>
<?php include_once('./inc/javascript.php') ?>



<script>
    $(document).ready(function(){
        $('#controle-lista').load('./blocos/controle-lista.php', {i:1});  
        
        <?php if ($_SESSION['evento']['tempo_atualiza']>0) { ?>
        function recarregarPagina() {
            $('#controle-lista').load('./blocos/controle-lista.php', { i: 1 }, function(response, status, xhr) {
                if (status == "error") {
                    window.location.reload();
                } else {
                    try {
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.error === 'session_expired') {
                            window.location.reload();
                        }
                    } catch (e) {
                        // A resposta não é um JSON válido, continue normalmente
                    }
                }
            });
        }
        setInterval(recarregarPagina, <?= $_SESSION['evento']['tempo_atualiza'] * 1000 ?>);
        <?php } ?>
    })
     
</script>

</body>
</html>
