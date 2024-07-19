<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>
<?php 

$sql = "SELECT tbprevenda.id_prevenda, tbresponsavel.id_responsavel, tbresponsavel.nome, tbresponsavel.cpf, tbprevenda.data_acesso, tbprevenda.datahora_solicita, tbprevenda.prevenda_status FROM tbprevenda inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
where tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.prevenda_status=1";
$pre = $connPDO->prepare($sql);
$pre->execute();
$row = $pre->fetchAll();

?>
</head>
<body class="theme-black">
<?php include('./inc/pageloader.php') ?>

<?php include_once('./inc/menu-overlay.php') ?>

<?php include('./inc/menu_topo.php') ?>
<?php include('./inc/menu_principal.php') ?>
<?php include('./inc/menu_lateral.php') ?>

<section class="content">
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Entradas cadastradas</h2>                    
                </div> 
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2>Responsáveis cadastrados</h2>
                        <ul class="header-dropdown">
                            <li><a href="#addResponsavelModal" data-toggle="modal" data-target="#addResponsavelModal"><i class="zmdi zmdi-plus-circle"></i></a></li>
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu slideUp">
                                    <li><a href="javascript:void(0);">...</a></li>
                                    <li><a href="javascript:void(0);">...</a></li>
                                </ul>
                            </li>                            
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>Acesso</th>
                                        <th>Responsável</th>
                                        <th>CPF</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>                                
                                <tbody>
                                    <?php  foreach ($row as $key => $value) { ?>
                                    <tr>
                                        <td><?= date('d/m/Y', $value['datahora_solicita']) ?></td>
                                        <td><?= $value['nome'] ?></td>
                                        <td><?= $value['cpf'] ?></td>
                                        <td><span class="badge badge-default"><?=  ($value['prevenda_status']==1?'Agendado':'Outro') ?></span></td>
                                        <td>
                                            <a class="btn btn-icon btn-neutral btn-icon-mini margin-0" href="entrada-form?item=<?=  $value['id_prevenda'] ?>"><i class="zmdi zmdi-sign-in"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>

<?php include('./inc/entrada-nova-modal.php') ?>
<?php include('./inc/javascript.php') ?>

</body>
</html>