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
                    <h2>Gerenciamento de usuários</h2>
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
                                <!-- <div class="card"> -->
                                    <div class="body">
                                        <form action="" method="post" id="form-basico">
                                            <h5>Informações iniciais do evento</h5>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="titulo" class="form-label">Nome</label>                               
                                                        <input type="text" class="form-control" placeholder="" value="" name="titulo" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Login</label>                               
                                                        <input type="text" class="form-control" placeholder="" value="" name="local" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Senha</label>                               
                                                        <input type="text" class="form-control" placeholder="" value="" name="cidade" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="" class="form-label">Perfil</label>                               
                                                        <select class="form-control show-tick p-0" name="status">
                                                            <option value="1">Usuário</option>
                                                            <option value="2">Master</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-info btn-round waves-effect">Adicionar</button>
                                                <input type="hidden" name="evento" value="<?= $id ?>">
                                            </div>
                                        </form>
                                    </div>

                                <!-- </div> -->
                            </div>
                        </div>


                

                    </div>
                </div>
            </div>

        <!-- </div> -->


        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="20%">Nome</th>
                                        <th width="20%">Login</th>
                                        <th width="20%" >Perfil</th>
                                        <th>Nova senha</th>
                                        <th>Editar</th>
                                        <th>Excluir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Fulano de tal</td>
                                        <td>fulano@detal</td>
                                        <td>Usuário</td>
                                        <td><button class="btn btn-sm btn-info waves-effect">Enviar nova senha</button></td>
                                        <td><button class="btn btn-sm btn-success waves-effect">Editar</button></td>
                                        <td><button class="btn btn-sm btn-danger waves-effect">Excluir</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        
        
        
                    
    </div>
</section>

<?php include('./inc/javascript.php'); ?>
</body>
</html>