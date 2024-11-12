<?php include_once('./inc/head.php') ?>
<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>
<?php include_once('./inc/funcoes.php') ?>

</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>


<?php  if (isset($_GET['d']) && isValidDate($_GET['d'])) {     

$sql_buscadata = "select * from tbcaixa_abre where status>0 and idevento=".$_SESSION['evento_selecionado']." and datacaixa='$dataRelata'";
$pre_buscadata = $connPDO->prepare($sql_buscadata);
$pre_buscadata->execute();

if ($pre_buscadata->rowCount() < 1) {
    ?>
    <script src="../assets/bundles/datatablescripts.bundle.js"></script>
    <script src="../assets/js/pages/tables/jquery-datatable.js"></script>
    <script src="../assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
    
    <script>
         swal({
                title: "Não existe caixa aberto para este dia, deseja abrir?",
                text: "Sub texto desta operação",
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
                    location.replace('./caixa-movimento');
                } else {
                    $.post('./blocos/caixa-movimento-abre.php', {d: '<?= $_GET['d'] ?>'})
                    
                }
            });
    </script>

    <?php
    
}

$row_buscadata = $pre_buscadata->fetchAll();

}

?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12 mt-4">
                    <h2>Movimento de caixa diário</h2>        
                </div>
            </div>
        </div>

<?php  
    if (isset($_GET['d']) && isValidDate($_GET['d'])) {  
         
    ?>

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
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0 row">
                                        <button data-toggle="modal" data-target="#modalAddMovimento" class="btn btn-primary btn-round waves-effect" id="addMovimento">Adicionar movimento</button>
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
                            <th width="10%">Item</th>
                            <th width="60%">Descrição</th>
                            <th width="10%">Valor</th>
                            <th width="10%">Tipo despesa</th>
                            <th width="10%">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                            </tbody>
                </table>
                

                                </div>
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
<?php //include_once('./inc/caixa-movimento-modal.php') ?>

<script>
    $(document).ready(function(){
        $('#dataFiltro').change(function(){
            var data = $(this).val();
            window.location = '?d='+data;
        });
    })
</script>


</body>
</html>