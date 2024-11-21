<?php include('./inc/head.php') ?>
</head>
<body class="theme-black">
<?php include('./inc/page-loader.php') ?>

<!-- Left Sidebar -->

<?php include('./inc/sidebar.php') ?>

<?php
$status_evento[1] = 'Em edição';
$status_evento[2] = 'Em andamento';
$status_evento[3] = 'Encerrado';

$sql_eventosAtivos = "SELECT * FROM tbevento WHERE status >0 order by status asc, inicio desc";
$pre_eventosAtivos = $connPDO->prepare($sql_eventosAtivos);
$pre_eventosAtivos->execute();
$row_eventosAtivos = $pre_eventosAtivos->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Main Content -->
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Eventos Ativos</h2>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row clearfix">

        <!-- <div class="row clearfix"> -->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">

                <?php if (count($row_eventosAtivos) == 0 ) {  ?>
                    <div class="body">
                        <div class="alert alert-info">
                            <strong>Atenção!</strong> Nenhum evento ativo cadastrado.
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Evento</th>
                                    <th>Local</th>
                                    <th>Cidade</th>
                                    <th>Data início</th>
                                    <th>Status</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 0;
                                foreach ($row_eventosAtivos as $key => $value) { ?>
                                <tr>
                                    <th scope="row"><?= ++$i ?></th>
                                    <td><?= $value['titulo'] ?></td>
                                    <td><?= $value['local'] ?></td>
                                    <td><?= $value['cidade'] ?></td>
                                    <td><?= date('d/m/Y', $value['inicio']) ?> </td>
                                    <td><span class="label label-success"><?= $status_evento[$value['status']] ?></span></td>
                                    <td><a href="edita-evento?id=<?= $value['id_evento'] ?>" class="button button-small edit" title="Edit">
                                        <i class="zmdi zmdi-edit"></i>
                                    </a></td>
                                </tr>
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                    </div>
                </div>
            </div>

        <!-- </div> -->
        
        
                    
    </div>
</section>

<?php include('./inc/javascript.php'); ?>
</body>
</html>