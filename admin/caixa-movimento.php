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

    if ($_GET['d'] != $_SESSION['get_d'] ) {
        die('<script>alert("Data informada é inválida para esta operação");location.replace("./caixa-fechamento");</script>');
    }

    $sql_buascacaixa = "SELECT * FROM tbcaixa_diario where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='".$_GET['d']."'";
    $pre_buascacaixa = $connPDO->prepare($sql_buascacaixa);
    $pre_buascacaixa->execute();
    $row_buascacaixa = $pre_buascacaixa->fetch(PDO::FETCH_ASSOC);
    // $row_buascacaixa = $pre_buascacaixa->fetchAll(PDO::FETCH_ASSOC);

    $sql_diario = "";
    // die(var_dump($row_buascacaixa));   
    if ($pre_buascacaixa->rowCount() > 0) {
        $idcxdiario = $row_buascacaixa['id'];
        $sql_diario = " id_caixadiario = $idcxdiario and ";
    }

    $sql_buscadata = "SELECT * from tbcaixa_abre where $sql_diario status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='".$_GET['d']."'";
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
                title: "Movimento de caixa diário",
                text: "Não existe movimento de caixa aberto para este dia, deseja abrir?",
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
                    $.post('./blocos/caixa-movimento-abre.php', {d: '<?= $_GET['d'] ?>'}, function(data){
                        // console.log(data);
                        //recebe data comoo json, verifica se é igual a 1, caso seja recarrega a página passando o valor do json na variável d 
                        let jsonResponse = JSON.parse(data);
                        if (jsonResponse.status == '1') {
                            location.replace('./caixa-movimento?d=<?= $_GET['d'] ?>');
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
                    <h2>Detalhamento de despesas</h2>        
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
                                <p class="">
                                        
                                        <button data-toggle="modal" data-target="#modalAddMovimento" class="btn btn-primary btn-round waves-effect" id="addMovimento">Adicionar movimento</button>
                                        
                                        <!-- <div class="col-md-3"><strong>Data:</strong></div> 
                                        <div class="col-md-6"><input class="form-control" type="date" name="" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value="<?= $_GET['d'] ?>"></div>  -->
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                    <a href="./caixa-fechamento?d=<?= $_GET['d'] ?>" class="btn btn-default" style="width: 45%">Fechamento de caixa</a>
                                    <a href="./caixa-movimento?d=<?= $_GET['d'] ?>" class="btn btn-info" style="width: 45%">Detalhamento de despesas</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        
                        <?php if ($row_buscadata[0]['status'] == 1) { ?>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 offset-md-6">
                                    <div class="row">
                                    
                                        <div class="col-6">
                                            
                                        </div>

                                        
                                        <!-- <div class="col-6">
                                            <p class="m-b-0 row">
                                                <button data-datarelata="<?= $row_buscadata[0]['id']?>" class="btn btn-success btn-round waves-effect" id="fecharCaixa">Fechar caixa</button>
                                            </p>
                                        </div> -->
                                        

                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        



                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive tabela-caixa">
                                    <?php include_once('./inc/caixa-movimento-tabela.php') ?>
                                </div>
                            </div>
                        </div>

                        
                        
                    </div>
                </div>
            </div>
        </div>

        



        <?php } /* else { ?>

            <!-- <div class="row clearfix">
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
        </div> -->

    

        <?php }  */ ?>

        
    </div>
</section>





<?php include_once('./inc/javascript.php') ?>
<?php if (isset($dataRelata) ) {  
    
    include_once('./inc/caixa-movimento-modal.php'); 

} ?>

<script>
    $(document).ready(function(){
        $('#dataFiltro').change(function(){
            var data = $(this).val();
            window.location = '?d='+data;
        });

        <?php  if (isset($dataRelata) ) {  ?>        

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
                    $.post('./blocos/caixa-movimento-fechar.php', {d: dataRelata}, function(data){
                        // console.log(data);
                        //recebe data comoo json, verifica se é igual a 1, caso seja recarrega a página passando o valor do json na variável d 
                        let jsonResponse = JSON.parse(data);
                        if (jsonResponse.status == 1) {
                            location.replace('./caixa-movimento?d=<?= $_GET['d'] ?>');
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