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

                

                    </div>
                </div>
            </div>

        <!-- </div> -->
        
        
                    
    </div>
</section>

<?php include('./inc/javascript.php'); ?>
</body>
</html>