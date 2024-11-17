<?php include_once('./inc/head.php') ?>
<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>
<?php include_once('./inc/funcoes.php');
function geraDatasSQL($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if ($dateTime === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }

    $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();
    $endTimestamp   = $dateTime->setTime(23, 59, 59)->getTimestamp();
    $i['start']     = $startTimestamp;
    $i['end']       = $endTimestamp;
    return $i;
}
?>

</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<?php  if (isset($_GET['d']) && isValidDate($_GET['d'])) {  
    
    //verifica se a data informada é maior que o dia atual

    $dataInfo   = geraDatasSQL($_GET['d']);
    $dataLimite = geraDatasSQL(date('Y-m-d', time()));

    // die(var_dump($dataInfo));
    
    if ( $dataInfo['end'] > $dataLimite['end'] ) {
        die('<script>alert("Data informada maior que a data atual");location.replace("./caixa-fechamento");</script>');
    }

    $_SESSION['get_d'] = $_GET['d'];

    $sql_buscadata = "select * from tbcaixa_diario where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='".$_GET['d']."'";
    // die($sql_buscadata);
    $pre_buscadata = $connPDO->prepare($sql_buscadata);
    $pre_buscadata->execute();

    if ($pre_buscadata->rowCount() < 1) {
        ?>
        <script src="../assets/bundles/datatablescripts.bundle.js"></script>
        <script src="../assets/js/pages/tables/jquery-datatable.js"></script>
        <script src="../assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
        
        <script>
            swal({
                    title: "Fechamento de caixa",
                    text: "Não existe caixa aberto para este dia, deseja abrir?",
                    type: "warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim",
                    cancelButtonText: "Não",
                    closeOnConfirm: false,
                    closeOnCancel: true,
                    showLoaderOnConfirm: true
                }, function (isConfirm) {
                    if (!isConfirm) {
                        location.replace('./caixa-fechamento');
                    } else {
                        $.post('./blocos/caixa-fechamento-abre.php', {d: '<?= $_GET['d'] ?>'}, function(data){
                            // console.log(data);
                            //recebe data comoo json, verifica se é igual a 1, caso seja recarrega a página passando o valor do json na variável d 
                            let jsonResponse = JSON.parse(data);
                            if (jsonResponse.status == '1') {
                                location.replace('./caixa-fechamento?d=<?= $_GET['d'] ?>');
                                // alert('mostra o reload')
                            }

                        })
                        
                    }
                });
        </script>

        <?php
        
    } else {
        $dataRelata = $_GET['d'];
        $row_buscadata = $pre_buscadata->fetchAll();
    }

} 

?>

<section class="content">    
    <div class="container">

    <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12 mt-4">
                    <h2>Fechamento de caixa diário</h2>        
                </div>
            </div>
        </div>


<?php  if (isset($dataRelata) ) {  ?>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">  
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0 row">
                                    <div class="col-md-3"><strong>Data:</strong></div> 
                                    <div class="col-md-6"><input class="form-control" type="date" name="" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value="<?= $_GET['d'] ?>"></div> 
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="./caixa-fechamento?d=<?= $_GET['d'] ?>" class="btn btn-info" style="width: 45%">Fechamento de caixa</a>
                                        <a href="./caixa-movimento?d=<?= $_GET['d'] ?>" class="btn btn-default" style="width: 45%">Detalhamento de despesas</a>
                                    </div>
                                </div>
                            </div>
                           

                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive tabela-caixa">
                                    <?php include_once('./inc/caixa-fechamento-formulario.php') ?>
                                </div>
                            </div>
                        </div>

                        <div class="mt-40"></div>

                        <div class="row">
                            <div class="col-12">
                            <?php if ($row_buscadata[0]['status'] == 1) { ?>
                                <div class="col-md-6 col-sm-6 offset-md-6">
                                    <div class="row">
                                    
                                        <div class="col-6">
                                            <p class="m-b-0 row">
                                            <button data-diario="<?= $row_buscadata[0]['id']?>" class="btn btn-primary btn-round waves-effect" id="salvaCaixa">Salvar caixa</button>
                                            </p>
                                        </div>
                                        
                                        <div class="col-6">
                                            <p class="m-b-0 row">
                                                <button data-diario="<?= $row_buscadata[0]['id']?>" class="btn btn-success btn-round waves-effect" id="fecharCaixa">Salvar e Fechar caixa</button>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>


        <?php } else { ?>

            <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">  
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0 row">
                                        <div class="col-md-3"><strong>Data:</strong></div> 
                                        <div class="col-md-6"><input class="form-control" type="date" name="" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value=""></div> 
                                </p>
                            </div>
                           
                        </div>
                         
                    </div>
                </div>
            </div>
        </div>

        <?php } ?>
        
    </div>
</section>


<?php include_once('./inc/javascript.php') ?>

<script>
    $(document).ready(function(){
        $('#dataFiltro').change(function(){
            var data = $(this).val();
            window.location = '?d='+data;
        });

        <?php  if (isset($dataRelata) && $row_buscadata[0]['status'] == 1) {  ?>        

        $('#fecharCaixa').click(function(){
            let dataRelata = $(this).data('datarelata');

            swal({
                title: "Fechar caixa",
                text: "Deseja realmente fechar o caixa?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, fechar!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post('./blocos/caixa-fechamento-fechar.php', {d: dataRelata}, function(data){
                        // console.log(data);
                        //recebe data comoo json, verifica se é igual a 1, caso seja recarrega a página passando o valor do json na variável d 
                        let jsonResponse = JSON.parse(data);
                        if (jsonResponse.status == 1) {
                            location.replace('./caixa-fechamento?d=<?= $_GET['d'] ?>');
                        }

                    })
                }
            });
        });
            
        <?php } ?>


    })
</script>


</body>
</html>