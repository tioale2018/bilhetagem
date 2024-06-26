<?php include_once('./inc/head.php') ?>

<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>


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
                                <p class="m-b-0 row">
                                        <div class="col-md-3"><strong>Data:</strong></div> 
                                        <div class="col-md-6"><input class="form-control" type="date" name="" id="dataFiltro" max="<?= date('Y-m-d', time()) ?>" value="<?= date('Y-m-d', time()) ?>"></div> 
                                </p>
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
        <th>Pacote</th>
        <th>H. Entrada</th>
        <th>H. Saída</th>
        <th>Pacote</th>
        <th>Permanencia</th>
        <!-- <th>Excedeu</th> -->
        <th>Valor</th>
    </tr>
</thead>
<tbody>

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
            window.location = 'caixa-basico.php?data='+data;
        });
    })
</script>


</body>
</html>