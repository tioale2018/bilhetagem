<?php include('./inc/head.php') ?>
</head>
<body class="theme-black">
<?php include('./inc/page-loader.php') ?>

<!-- Left Sidebar -->

<?php include('./inc/sidebar.php') ?>

<?php
/*
$status_evento[1] = 'Em edição';
$status_evento[2] = 'Em andamento';
$status_evento[3] = 'Encerrado';

$sql_eventosAtivos = "SELECT * FROM tbevento WHERE status >0 order by status asc, inicio desc";
$pre_eventosAtivos = $connPDO->prepare($sql_eventosAtivos);
$pre_eventosAtivos->execute();
$row_eventosAtivos = $pre_eventosAtivos->fetchAll(PDO::FETCH_ASSOC);
*/
?>

<!-- Main Content -->
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Cadastrar novo evento</h2>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row clearfix">

        <!-- <div class="row clearfix"> -->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">

                <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <form action="" method="post" id="form-basico">
                    <h5>Informações iniciais do evento</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="titulo" class="form-label">Título</label>                               
                                <input type="text" class="form-control" placeholder="" value="" name="titulo" required />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="" class="form-label">Local</label>                               
                                <input type="text" class="form-control" placeholder="" value="" name="local" required />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="" class="form-label">Cidade</label>                               
                                <input type="text" class="form-control" placeholder="" value="" name="cidade" required />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="form-label">Capacidade</label>                               
                                <input type="text" class="form-control" placeholder="" value="" name="capacidade"  />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="form-label">Data de início</label>                               
                                <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="" name="inicio" required  />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="form-label">Data de fim (estimada)</label>                               
                                <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="" name="fim"  />
                            </div>
                        </div>
                        
                        
                    </div>
                    <div>
                        <button type="submit" class="btn btn-info btn-round waves-effect">Adicionar e continuar</button>
                        <input type="hidden" name="evento" value="<?= $id ?>">
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


                

                    </div>
                </div>
            </div>

        <!-- </div> -->
        
        
                    
    </div>
</section>

<?php include('./inc/javascript.php'); ?>
</body>
</html>