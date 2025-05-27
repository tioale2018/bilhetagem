<?php 
die(var_dump($_SESSION));
session_start();

if (!isset($_SESSION['dadosResponsavel'])) {
    header('Location: index.php');
}
//include_once("./inc/cad-participantes-regras.php");
include('./inc/conexao.php');
include('./inc/funcoes.php');

$evento            = $_SESSION['row'];
$evento_atual      = $_SESSION['evento_atual'];
$id                = $_SESSION['cpf'];
$dados_responsavel = $_SESSION['dadosResponsavel'];
$idPrevendaAtual   = $_SESSION['idPrevenda'];
$hashEvento        = $_SESSION['hash_evento'];

// Add CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

$sql = "SELECT count(*) as total  FROM tbentrada WHERE id_prevenda=:idprevenda and previnculo_status=1 and ativo=1 and autoriza=0";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();
$total = $row[0]['total'];

if ($total>0) {
    header('Location: cadastro.php');
} 


$sql = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbprevenda.id_responsavel, tbprevenda.id_evento, tbprevenda.prevenda_status, tbresponsavel.nome as nomeresponsavel, tbresponsavel.cpf, tbresponsavel.telefone1, tbresponsavel.telefone2, tbperfil_acesso.titulo, tbprevenda.id_prevenda
from tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
inner join tbperfil_acesso on tbperfil_acesso.idperfil=tbentrada.perfil_acesso
where tbentrada.previnculo_status=1 and tbprevenda.prevenda_status=9 and tbentrada.id_prevenda=:idprevenda";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();

if ($pre->rowCount()<1) {
    session_destroy();
    header('Location: /'.$hashEvento);
}

$horaAgora = time();

//encerra e envia prevenda para operador
$sql_atualiza_prevenda = "update tbprevenda set prevenda_status=1, pre_reservadatahora='$horaAgora' where id_prevenda=:idprevenda";
$pre_atualiza_prevenda = $connPDO->prepare($sql_atualiza_prevenda);
$pre_atualiza_prevenda->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);
$pre_atualiza_prevenda->execute();
?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php

include_once("./inc/head.php");

?>

</head>
<body class="theme-black">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="assets/images/logo.svg" width="48" height="48" alt="Alpino"></div>
        <p>Aguarde</p>        
    </div>
</div>


<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Pré cadastro realizado</h2>
                </div>            
                <!-- <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{Página 01}</a></li>
                        <li class="breadcrumb-item active">{Página atual}</li>
                    </ul>
                </div> -->
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">                                
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0"><strong>Data: </strong> <?= date('d/m/Y H:i', $horaAgora) ?></p>
                                <p class="m-b-0"><strong>Responsável: </strong> <?= $row['0']['nomeresponsavel'] ?></p>
                                <p class="m-b-0"><strong>CPF: </strong> <?= formatarCPF($row['0']['cpf']) ?></p>
                                <p class="m-b-0"><strong>Telefone: </strong> <?= $row['0']['telefone1'] ?><br></p>                                
                            </div>
                        </div>
                        <div class="mt-40"></div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>                                                        
                                                <th>Participante</th>                                                        
                                                <th>Perfil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            foreach ($row as $key => $value) { 
                                                // $total = $total + $value['valor'];
                                                ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>                                                        
                                                <td><?= $value['nome'] ?></td>
                                                <td><?= $value['titulo'] ?></td>
                                                
                                            </tr>

                                            <?php }  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div>
                        <?= $evento[0]['msg_fimreserva'] ?>
                        </div>

                        <hr>
                                                   
                            <div class="hidden-print col-md-12 text-right">
                                <div class="row">
                                    
                                    
                                    <div class="col-3">
                                        <a href="/<?= $hashEvento ?>" class="btn btn-raised btn-primary btn-round">Retornar</a>
                                    </div>
                                </div>
        
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php //include('./inc/menu_topo.php') ?>
<?php //include('./inc/menu_principal.php') ?>
<?php //include('./inc/menu_lateral.php') ?>


<?php //include('./inc/cad-participante-modal.php') ?>

<?php include('./inc/javascript.php') ?>




</body>
</html>