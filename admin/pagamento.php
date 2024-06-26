<?php include_once('./inc/head.php') ?>
<?php include_once('./inc/conexao.php') ?>

<?php
if ((!isset($_GET['item'])) || (!is_numeric($_GET['item']))) {
    header('Location: entrada-nova.php');
}

$idprevenda = $_GET['item'];

$sql = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor from tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

$pre->execute();

if ($pre->rowCount()<1) {
    header('Location: controle.php');
}

$row = $pre->fetchAll();

?>

</head>
<body class="theme-black">
<?php include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu-overlay.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Pagamento na entrada</h2>
                </div>            
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{Página 01}</a></li>
                        <li class="breadcrumb-item active">{Página atual}</li>
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
                                <p class="m-b-0"><strong>Data: </strong> 01/01/2000 18:03</p>
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
                                                <th>Participante</th>                                                        
                                                <th>Pacote</th>
                                                <th>Duração</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            $financeiro_detalha = array();
                                            foreach ($row as $key => $value) { 
                                                $total = $total + $value['valor'];

                                                $financeiro_detalha['identrada'][] = $value['id_entrada'];
                                                $financeiro_detalha['prevenda'][]  = $idprevenda;
                                                $financeiro_detalha['apagar'][]    = $value['valor'];

                                                $financeiro_detalha['pgtoinout'][] = 1;

                                                ?>
                                                
                                            <tr>
                                                <td><?= $key + 1 ?></td>                                                        
                                                <td><?= $value['nome'] ?></td>
                                                <td><?= $value['descricao'] ?></td>
                                                <td><?= $value['duracao'].'min' ?></td>
                                                <td><?= $value['valor'] ?></td>
                                            </tr>

                                            <?php }  ?>
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
                        <form action="" method="post" id="formpgto" class="row">
                            
                            <div class="col-md-4">
                                <table class="table m-b-0">
                                    <thead>
                                        <tr>
                                            
                                            <th>Forma de pagamento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            
                                            <td>
                                            <select class="form-control show-tick p-0" name="tipopgto" id="ftipopgto" required>
                                                <option value="">Escolha</option>
                                                <option value="1">Cartão de crédito</option>
                                                <option value="2">Cartão de débito</option>
                                                <option value="3">Dinheiro</option>
                                                <option value="4">Pix</option>
                                                <option value="5">Misto</option>
                                                
                                            </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7 text-right">
                                <p class="m-b-0"><b>Valor a pagar:</b>
                                <span id="subtotal" style="display: none"><?= $total ?></span></p>
                                <h3 class="m-b-0 m-t-10">R$ <?= number_format($total, 2, ',', '.') ?></h3>
                            </div>                                    
                            <div class="hidden-print col-md-12 text-right js-sweetalert">
                                <input type="hidden" name="idprevenda" value="<?= $_GET['item'] ?>">
                                <hr>
                                <input type="hidden" name="idprevenda" value="<?= $idprevenda ?>">
                                
                                <input type="hidden" name="pgto" value="<?= $total ?>">
                                <input type="hidden" name="vinculados" value="<?= $lst_vinculos ?>">
                                <input type="hidden" name="horafinaliza" value="<?= $hora_finaliza ?>">
                                <?php
                                    $financeiro_detalha_json = json_encode($financeiro_detalha);
                                ?>
                                <input type="hidden" name="pgtodetalha" value='<?= htmlspecialchars($financeiro_detalha_json, ENT_QUOTES, 'UTF-8') ?>'>
                                
                                <button class="btn btn-raised btn-primary btn-round" type="submit">Efetuar pagamento</button>
                            </div>
                        </form>

                        <form action="" id="formImpressao">
                            <input type="hidden" value="<?= $idprevenda ?>" name="idprevenda">
                            <input type="hidden" value="1" name="entradasaida">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <iframe id="printFrame" name="printFrame" style="display:none"></iframe>
</section>



<?php include_once('./inc/javascript.php') ?>

<script src="./js/impressao.js"></script>
<script>

$(document).ready(function(){
    $('#formpgto').submit(function(e){
        let formAtual = $(this);
        e.preventDefault();

        swal({
            title: "Deseja efetuar este pagamento",
            text: "Sub texto desta operação",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, efetuar pagamento",
            cancelButtonText: "Não, cancelar e retornar",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if(isConfirm) {
                    $.post('./blocos/efetua-pagamento.php', formAtual.serialize(), function(data){
                        
                        swal({
                                title: "Concluído", 
                                text: "Pagamento efetuado com sucesso!",
                                showCancelButton: false,
                                type: "success"
                                }, function(){
                                    //location.href="controle.php";
                                    printAnotherDocument('comprovante.php', '#formImpressao');
                            })
                        //console.log(data);
                    });
                }
                
            } 
        });
    });
});

</script>


</body>
</html>