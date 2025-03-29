<?php include_once('./inc/head.php') ?>
<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>
<?php include_once('./inc/funcoes.php');
function geraDatasSQL($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if ($dateTime === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }

    $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();
    $endTimestamp   = $dateTime->setTime(23, 59, 59)->getTimestamp();
    $i['start']     = $startTimestamp;
    $i['end']       = $endTimestamp;
    return $i;
}
?>

</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<?php  if (isset($_GET['d']) && isValidDate($_GET['d'])) {  
    
    //verifica se a data informada é maior que o dia atual

    $dataInfo   = geraDatasSQL($_GET['d']);
    $dataLimite = geraDatasSQL(date('Y-m-d', time()));

    // die(var_dump($dataInfo));
    
    if ( $dataInfo['end'] > $dataLimite['end'] ) {
        die('<script>alert("Data informada maior que a data atual");location.replace("./caixa-fechamento");</script>');
    }

    $_SESSION['get_d'] = $_GET['d'];

    $sql_buscadata = "select * from tbcaixa_diario where status>0 and idevento=:idevento and datacaixa=:datacaixa";
    // die($sql_buscadata);
    $pre_buscadata = $connPDO->prepare($sql_buscadata);
    $pre_buscadata->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
    $pre_buscadata->bindParam(':datacaixa', $_GET['d'], PDO::PARAM_STR);
    $pre_buscadata->execute();

    if ($pre_buscadata->rowCount() < 1) {
        ?>
        <script src="../assets/bundles/datatablescripts.bundle.js"></script>
        <script src="../assets/js/pages/tables/jquery-datatable.js"></script>
        <script src="../assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
        
        <script>
            swal({
                    title: "Abrir novo caixa",
                    text: "Não existe caixa aberto para este dia, deseja abrir?",
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
                        location.replace('./caixa-fechamento');
                    } else {
                        $.post('./blocos/caixa-fechamento-abre.php', {d: '<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>'}, function(data){
                            // console.log(data);
                            //recebe data comoo json, verifica se é igual a 1, caso seja recarrega a página passando o valor do json na variável d 
                            let jsonResponse = JSON.parse(data);
                            if (jsonResponse.status == '1') {
                                location.replace('./caixa-fechamento?d=<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>');
                                // alert('mostra o reload')
                            }

                        })
                        
                    }
                });
        </script>

        <?php
        
    } else {
        $dataRelata = $_GET['d'];
        $row_buscadata = $pre_buscadata->fetch(PDO::FETCH_ASSOC);
    }

} 

// die(var_dump($row_buscadata));


?>

<section class="content">    
    <div class="container">

    <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12 mt-4">
                    <h2>Fechamento de caixa diário</h2>        
                </div>
            </div>
        </div>


<?php  if (isset($dataRelata) ) {  
    // echo var_dump($row_buscadata);
    $diarioAtivo = $row_buscadata['id'];
    $sql_caixaformulario = "SELECT * FROM tbcaixa_formulario WHERE status > 0 AND idevento = :idevento AND idcaixadiario = :idcaixadiario";
    $pre_caixaformulario = $connPDO->prepare($sql_caixaformulario);
    $pre_caixaformulario->bindParam(':idevento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
    $pre_caixaformulario->bindParam(':idcaixadiario', $diarioAtivo, PDO::PARAM_INT);
    $pre_caixaformulario->execute();
    
    if ($pre_caixaformulario->rowCount() < 1) {
        die('<script>alert("Formulário inativo para este dia, consulte o administrador do sistema.");location.replace("./caixa-fechamento");</script>');
    }
    
    $row_caixaformulario = $pre_caixaformulario->fetch(PDO::FETCH_ASSOC);
    // echo var_dump($row_caixaformulario);


    if ($row_buscadata['status'] == 1) {
        //verifica os valores do caixa do dia
        function geraValoresCaixa($date) {
            $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        
            if ($dateTime === false) {
                throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
            }
            $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();
            $endTimestamp = $dateTime->setTime(23, 59, 59)->getTimestamp();
        
            $sql = "SELECT sum(valor) as valor, forma_pgto
            FROM tbfinanceiro
            inner join tbprevenda on tbprevenda.id_prevenda=tbfinanceiro.id_prevenda
            where tbfinanceiro.forma_pgto>0 and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbfinanceiro.ativo=1 and tbfinanceiro.hora_pgto BETWEEN {$startTimestamp} AND {$endTimestamp} 
            GROUP by tbfinanceiro.forma_pgto";
       
            return $sql;
        }

        $sql_valoresCaixa = geraValoresCaixa($dataRelata);
        $pre_valoresCaixa = $connPDO->prepare($sql_valoresCaixa);
        $pre_valoresCaixa->execute();
        $row_valoresCaixa = $pre_valoresCaixa->fetchAll(PDO::FETCH_ASSOC);

        // die(var_dump($row_valoresCaixa));

        $cartao   = 0;
        $dinheiro = 0;
        $pix      = 0;

        foreach ($row_valoresCaixa as $key => $value) {
            switch ($value['forma_pgto']) {
                case 1:
                    $cartao = $cartao + $value['valor'];
                    break;
                case 2:
                    $cartao = $cartao + $value['valor'];
                    break;
                case 3:
                    $dinheiro = $value['valor'];
                    break;
                case 4:
                    $pix = $value['valor'];
                    break;
                default:
                    break;
            }

            // echo var_dump() . "<br>";
        }

    } elseif ($row_buscadata['status'] == 2) {
        $cartao   = $row_caixaformulario['sis_vendacar'];
        $dinheiro = $row_caixaformulario['sis_vendadin'];
        $pix      = $row_caixaformulario['sis_vendapix'];

        //total tickets
       
    }

     //tickets
        
    $dataRelata = $_GET['d'];

    $dataSql = geraDatasSQL($dataRelata);
/*
    $total_tickets = 0;
    $sql_buscatickets = "SELECT count(tbentrada.id_pacote) as total_vendido FROM tbentrada inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda where tbentrada.id_pacote>0 and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbentrada.datahora_entra BETWEEN ".$dataSql['start']." AND ".$dataSql['end'];
    $pre_buscatickets = $connPDO->prepare($sql_buscatickets);
    $pre_buscatickets->execute();
    $row_buscatickets = $pre_buscatickets->fetch(PDO::FETCH_ASSOC);
 */
    // $sis_total_tickets = $row_buscatickets['total_vendido'];
    $sis_total_tickets = $row_caixaformulario['sis_totaltickets'];
        


    $total_tickets = $row_caixaformulario['total_tickets'];

   

    /*
    $sql_tickets = "select * from tbprevenda where id_evento=".$_SESSION['evento_selecionado']." and data_venda='$dataRelata'";
    $pre_tickets = $connPDO->prepare($sql_tickets);
    $pre_tickets->execute();
    $row_tickets = $pre_tickets->fetchAll(PDO::FETCH_ASSOC);
    foreach ($row_tickets as $key => $value) {
        $total_tickets = $total_tickets + 1;
    }
    */

    $total_entradas = $dinheiro + $row_caixaformulario['val_abrecaixa'];


    
$sql_buscaMovimento = "SELECT sum(valor) as total, tbcaixa_tipodespesa.descricao
FROM tbcaixa_movimento 
inner join tbcaixa_tipodespesa on tbcaixa_tipodespesa.id=tbcaixa_movimento.idtipodespesa
WHERE tbcaixa_movimento.idcaixaabre = :diarioAtivo and tbcaixa_movimento.ativo=1
group by idtipodespesa";

$pre_buscaMovimento = $connPDO->prepare($sql_buscaMovimento);
$pre_buscaMovimento->bindParam(':diarioAtivo', $diarioAtivo, PDO::PARAM_INT);
$pre_buscaMovimento->execute();
$row_buscaMovimento = $pre_buscaMovimento->fetchAll(PDO::FETCH_ASSOC);

    ?>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">  
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p class="m-b-0 row">
                                    <div class="col-md-3"><strong>Data:</strong></div> 
                                    <div class="col-md-6"><input class="form-control" type="date" name="" id="dataFiltro" max="<?= htmlspecialchars(date('Y-m-d', time()), ENT_QUOTES) ?>" value="<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>"></div> 
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="./caixa-fechamento?d=<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>" class="btn btn-info" style="width: 45%">Fechamento de caixa</a>
                                        <a href="./caixa-movimento?d=<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>" class="btn btn-default" style="width: 45%">Detalhamento de saídas</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive tabela-caixa">
                                    <form action="" method="post" id="formCaixa">
                                        <?php include_once('./inc/caixa-fechamento-formulario.php') ?>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="mt-40"></div>

                        <div class="row">
                            <div class="col-12">
                            <?php if ($row_buscadata['status'] == 1) { ?>
                                <div class="col-md-6 col-sm-6 offset-md-6">
                                    <div class="row">
                                    
                                        <div class="col-6">
                                            <p class="m-b-0 row">
                                            <button type="button" data-diario="<?= $row_buscadata['id']?>" class="btn btn-primary btn-round waves-effect" id="salvaCaixa">Salvar caixa</button>
                                            </p>
                                        </div>
                                        
                                        <div class="col-6">
                                            <p class="m-b-0 row">
                                                <button type="button" data-diario="<?= $row_buscadata['id']?>" class="btn btn-success btn-round waves-effect" id="fecharCaixa">Salvar e Fechar caixa</button>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            <?php } ?>
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

<script>
    $(document).ready(function(){
        let previousValue = '';

        $('#dataFiltro').on('focus', function() {
            // Armazena o valor atual antes da mudança
            previousValue = $(this).val();
        });

        $('#dataFiltro').on('change', function(e) {
            let objThis = $(this);
            let currentValue = objThis.val(); // Novo valor após a mudança

            if (previousValue!='') {
                if (confirm('Deseja alterar a data?')) {
                    window.location = '?d=' + currentValue;        
                } else {
                    objThis.val(previousValue);
                    e.preventDefault();
                }
            } else {
                window.location = '?d=' + currentValue;  
            }
            
        });

        <?php  if (isset($dataRelata) && $row_buscadata['status'] == 1) {  ?>        

        $('#fecharCaixa').click(function(){
            let dataRelata = $(this).data('datarelata');

            swal({
                title: "Fechar caixa",
                text: "Deseja realmente fechar o caixa?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, fechar!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post('./blocos/caixa-fechamento-fechar.php', {d: dataRelata}, function(data){
                        // console.log(data);
                        //recebe data comoo json, verifica se é igual a 1, caso seja recarrega a página passando o valor do json na variável d 
                        let jsonResponse = JSON.parse(data);
                        if (jsonResponse.status == 1) {
                            location.replace('./caixa-fechamento?d=<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>');
                        }

                    })
                }
            });
        });

        $('#salvaCaixa').click(function(){
            $.post('./blocos/caixa-fechamento-salvar.php', $('#formCaixa').serialize(), function(data){
                let jsonResponse = JSON.parse(data);
                if (jsonResponse.status == 1) {
                    swal({
                        title: "Caixa salvo!",
                        text: "Salvo com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    }, function () {
                        location.replace('./caixa-fechamento?d=<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>');
                    });
                }
            });
        });
           
        $('#fecharCaixa').click(function(){
            swal({
                    title: "Fechamento de caixa",
                    text: "Confirma o fechamento do caixa para este dia?",
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
                    if (isConfirm) {
                        $.post('./blocos/caixa-fechamento-fechar.php', $('#formCaixa').serialize(), function(data){
                            console.log(data);
                            setTimeout(() => {
                                swal({
                                title: "Concluído", 
                                text: "Caixa fechado com sucesso!",
                                showCancelButton: false,
                                type: 'success'
                                }, function(){
                                    location.replace('./caixa-fechamento');            
                                });
                            }, 2000);
                        })
                    }
                });
        });        

        <?php } elseif (isset($dataRelata) && $row_buscadata['status'] == 2) { ?>
            $('input.form-caixa, textarea.form-caixa').prop('readonly', true);
        <?php } ?>
       
    });
</script>


<?php if (isset($_GET['d']) && isValidDate($_GET['d'])) { ?>
    <script src="./js/vanilla-masker.js"></script>
    <script>
        $(document).ready(function() {
            $('.despesas').click(function() {
                location.href = "./caixa-movimento?d=<?= htmlspecialchars($_GET['d'], ENT_QUOTES) ?>"
            })
        });
    </script>    



<script>

function formataMoney() {
    document.querySelectorAll('.money').forEach(input => {
        // Adiciona o evento de entrada para controlar valores negativos e formatação
        input.addEventListener('input', () => {
            let value = input.value.replace(/[^\d,-]/g, ''); // Remove caracteres inválidos
            const isNegative = value.startsWith('-'); // Verifica se é negativo
            
            // Remove o sinal negativo temporariamente para formatação
            value = value.replace('-', '');
            
            // Aplica a máscara com VMasker (sem o delimitador no início)
            const maskedValue = VMasker.toMoney(value, {
                separator: ',',
                delimiter: '.',
                precision: 2,
            });
            
            // Reinsere o sinal negativo, se necessário
            input.value = isNegative ? `-${maskedValue}` : maskedValue;
        });

        // Permite digitar "-" manualmente
        input.addEventListener('keydown', (e) => {
            if (e.key === '-' && !input.value.includes('-')) {
                input.value = '-' + input.value;
                e.preventDefault(); // Evita comportamentos adicionais do navegador
            }
        });
    });
}

// Chama a função após o carregamento da página ou para os campos necessários
formataMoney();

function calcularValores() {
    // Função para converter valor formatado para decimal
    function formatToDecimal(input) {
        return parseFloat(input.replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Selecionando os campos necessários
    const valAbreCaixa = document.querySelector('input[name="fval_abrecaixa"]').value;
    const dinheiro = document.querySelector('input[name="fval_dinheiro"]').value;
    const totalEntradas = document.querySelector('input[name="fval_entradas"]').value;
    const despesas = document.querySelector('input[name="fval_despesas"]').value;
    const sangria = document.querySelector('input[name="fval_final"]').value;
    const valExtra = document.querySelector('input[name="fval_extra"]').value;
    const valorEntradas = formatToDecimal(dinheiro) + formatToDecimal(valAbreCaixa);
    
    const saldoTotal = valorEntradas - formatToDecimal(despesas);
    const valorExtra = formatToDecimal(sangria) - saldoTotal;

    document.querySelector('input[name="fval_entradas"]').value = valorEntradas.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.querySelector('input[name="fval_total"]').value = saldoTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.querySelector('input[name="fval_extra"]').value = valorExtra.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    if (valorExtra>0) {
        document.querySelector('#sinalExtra').innerHTML = '(+)';
        document.querySelector('#rotuloExtra').style.color = 'black';
    } else if (valorExtra<0) {
        document.querySelector('#sinalExtra').innerHTML = '(-)';
        document.querySelector('#rotuloExtra').style.color = 'red';
    } else {
        document.querySelector('#sinalExtra').innerHTML = '';
        document.querySelector('#rotuloExtra').style.color = 'black';
    }

formataMoney();
}

document.querySelectorAll('input[name="fval_abrecaixa"], input[name="fval_final"], input[name="ftickets"], input[name="fcartao"], input[name="fpix"], input[name="fobs"]').forEach(input => {
    input.addEventListener('change', () => {
        
        $.post('./blocos/caixa-fechamento-salvar.php', $('#formCaixa').serialize(), function(data){
            calcularValores();    
        })
    });
});

// Inicializar máscara e cálculos ao carregar a página
formataMoney();
calcularValores();

</script>

<?php } ?>

</body>
</html>