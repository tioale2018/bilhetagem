<?php include_once('./inc/head.php') ?>
<?php include_once('./inc/conexao.php') ?>

<?php
if ((!isset($_GET['item'])) || (!is_numeric($_GET['item']))) {
    header('Location: entrada-nova.php');
}

$idprevenda = $_GET['item'];

$sql = "select tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor, tbprevenda.id_responsavel, tbresponsavel.nome as nomeresponsavel, tbresponsavel.email, tbresponsavel.telefone1, tbresponsavel.telefone2 from tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

$pre->execute();

if ($pre->rowCount()<1) {
    header('Location: controle.php');
}

$row = $pre->fetchAll();
$hora_finaliza = time();

?>

<style>
.page-block {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.4);
    z-index: 99999;
    display: none;
}
</style>
</head>
<body class="theme-black">
<div class="page-block"></div>        
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
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">                                
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                            <p class="m-b-0"><strong>Data: </strong> <?= date('d/m/Y H:i', $hora_finaliza); ?></p>
                                <p class="m-b-0"><strong>Status: </strong> <span class="badge badge-warning m-b-0">Aguardando pagamento</span></p>
                                <p><strong>Ticket ID: </strong> #<?= $row[0]['id_prevenda'] ?></p>
                                
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                            <address>
                                    <strong><?= $row[0]['nomeresponsavel'] ?></strong><br>
                                    <?= $row[0]['telefone1'] ?><br>
                                    <?= $row[0]['email'] ?>
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
                                                <td>R$ <?= number_format($value['valor'], 2, ',', '.') ?></td>
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
                        <form action="./blocos/efetua-pagamento.php" method="post" id="formpgto" class="row">
                            
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
                                                <!-- <option value="5">Misto</option> -->
                                                
                                            </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7 text-right">
                                <p class="m-b-0"><b>Valor a pagar:</b>
                                <span id="subtotal" style="display: none"><?= htmlspecialchars($total) ?></span></p>
                                <h3 class="m-b-0 m-t-10">R$ <?= number_format($total, 2, ',', '.') ?></h3>
                            </div>                                    
                            <div class="hidden-print col-md-12 text-right js-sweetalert">
                                <input type="hidden" name="idprevenda" value="<?= htmlspecialchars($_GET['item']) ?>">
                                <hr>
                                <input type="hidden" name="idprevenda" value="<?= htmlspecialchars($idprevenda) ?>">
                                
                                <input type="hidden" name="pgto" value="<?= htmlspecialchars($total) ?>">
                                <?php /*
                                <input type="hidden" name="vinculados" value="<?= $lst_vinculos ?>">
                                */
                                ?>
                                <input type="hidden" name="horafinaliza" value="<?= htmlspecialchars($hora_finaliza) ?>">
                                <?php
                                    $financeiro_detalha_json = json_encode($financeiro_detalha);
                                    //$_SESSION['financeiro_detalha'] = htmlspecialchars($financeiro_detalha_json, ENT_QUOTES, 'UTF-8');
                                ?>
                                <input type="hidden" name="pgtodetalha" value='<?= htmlspecialchars($financeiro_detalha_json, ENT_QUOTES, 'UTF-8') ?>'>
                                
                                <button class="btn btn-raised btn-primary btn-round" type="submit">Efetuar pagamento</button>
                            </div>
                        </form>
                        <form action="" id="formImpressao">
                            <input type="hidden" value="<?= htmlspecialchars($idprevenda) ?>" name="idprevenda">
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

        $('#formpgto button[type=submit]').attr('disabled', true);
        let formAtual = $(this);
        e.preventDefault();

        swal({
            title: "Deseja efetuar este pagamento?",
            text: "Confirma o pagamento desta entrada?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, efetuar pagamento",
            cancelButtonText: "Não, cancelar e retornar",
            closeOnConfirm: false,
            closeOnCancel: true,
            showLoaderOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                    
                $.post('./blocos/efetua-pagamento.php', formAtual.serialize(), function(data){
                    setTimeout(() => {
                        console.log(data);
                        var jsonResponse = JSON.parse(data);
                        var mensagem = (jsonResponse.error === 1) ? 'Pagamento efetuado com sucesso!' : 'Ocorreu um erro ao efetuar o pagamento, entre em contato com o suporte!';
                        var tipoJanela = (jsonResponse.error === 1) ? 'success' : 'error';
                        swal({
                                title: "Concluído", 
                                text: mensagem,
                                showCancelButton: false,
                                type: tipoJanela
                                }, function(){
                                    printAnotherDocument('comprovante.php', '#formImpressao');
                        });
                    }, 2000);            
                });
            } else {
                $('#formpgto button[type=submit]').attr('disabled', false);
            }
        });

    });
});

</script>


</body>
</html>