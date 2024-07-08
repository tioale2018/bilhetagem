<?php 
include_once('./inc/head.php');
include_once('./inc/conexao.php');
include_once('./inc/funcoes-gerais.php');
?>

<style>
    .expired {
        background-color: #f7a8ad;
    }
</style>

<?php

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3  and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." order by tbentrada.datahora_entra";
$pre = $connPDO->prepare($sql);

$pre->execute();
$row = $pre->fetchAll();

?>
</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu-overlay.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<!-- Main Content -->
<section class="content">
    <div class="container">
        <div class="block-header">
            <div class="row clearfix mt-3">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h2 class="">Controle de recreação: <?= $_SESSION['evento_titulo'] ?></h2>                    
                </div>            
                <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{Página 01}</a></li>
                        <li class="breadcrumb-item active">{Página atual}</li>
                    </ul>
                </div> -->
            </div>
        </div>
       
        <?php //include_once('./inc/cards-dashboard.php') ?>
        <?php /* ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <form>
                            <div class="row clearfix">
                                <div class="center">
                                    <button type="button" class="btn btn-raised btn-primary btn-round waves-effect m-l-20">LOGIN</button>          
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php */ ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="body project_report">
                        <div class="table-responsive">
                            <table class="table m-b-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome/Responsável</th>
                                        <th>Idade</th>
                                        <th>Hora entrada</th>
                                        <th>Hora saída</th>
                                        <th>Tempo Decorrido</th>
                                        <th>Saída</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php  if (count($row) > 0) {     ?>
                                    <?php  foreach ($row as $key => $value) {     ?>
                                    <tr>
                                        <td class="project-title">
                                            <h6><a href="#"><?= $value['nome'] ?></a></h6>
                                            <small>Resp.: <?= $value['responsavel'] ?></small>
                                        </td>
                                        <td><?= calcularIdade($value['nascimento']) ?> Anos</td>
                                        <td>
                                            <div class="hora-entrada"><?= date('H:i:s', $value['datahora_entra']) ?></div>
                                            <small><?= calculaDuracao($value['duracao']); ?></small>
                                        </td>
                                        <td>
                                            <div class="hora-saida"><?= somarMinutos($value['datahora_entra'], $value['duracao']) ?></div>
                                            <!-- <small>+<?= calculaDuracao($value['tolerancia']); ?></small> -->
                                        </td>                                        
                                        <td><span class="tdecorrido"></span></td>
                                        <td class="project-actions">
                                            <!-- <a href="#modalSaida" data-toggle="modal" data-target="#modalSaida" class="btn btn-neutral btn-sm"><i class="zmdi zmdi-sign-in"></i></a> -->
                                            <button data-idprevenda="<?= $value['id_prevenda'] ?>" type="button" data-toggle="modal" data-target="#modalSaida" class="btn btn-neutral btn-sm btnModalSaida"><i class="zmdi zmdi-sign-in"></i></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                        <tr><td colspan="6" style="text-align: center">Nenhum resultado encontrado</td></tr>
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

<?php include_once('./inc/controle-modal.php') ?>


<?php include_once('./inc/javascript.php') ?>

<script>
    /*
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os elementos que mostram o tempo decorrido
        var elementosTempoDecorrido = document.querySelectorAll('.tdecorrido');

        // Função para calcular o tempo decorrido
        function calcularTempoDecorrido(horaEntrada) {
            var agora = new Date();
            var horaAtual = agora.getHours();
            var minutoAtual = agora.getMinutes();
            var segundoAtual = agora.getSeconds();

            var partesHoraEntrada = horaEntrada.split(':');
            var horaEntrada = parseInt(partesHoraEntrada[0]);
            var minutoEntrada = parseInt(partesHoraEntrada[1]);
            var segundoEntrada = parseInt(partesHoraEntrada[2]);

            var diferencaHoras = horaAtual - horaEntrada;
            var diferencaMinutos = minutoAtual - minutoEntrada;
            var diferencaSegundos = segundoAtual - segundoEntrada;

            // Garante que os valores da diferença estejam dentro do intervalo correto
            if (diferencaSegundos < 0) {
                diferencaSegundos += 60;
                diferencaMinutos--;
            }
            if (diferencaMinutos < 0) {
                diferencaMinutos += 60;
                diferencaHoras--;
            }
            if (diferencaHoras < 0) {
                diferencaHoras += 24;
            }

            // Formata a diferença para o formato HH:MM:SS
            var horasFormatadas = ('0' + diferencaHoras).slice(-2);
            var minutosFormatados = ('0' + diferencaMinutos).slice(-2);
            var segundosFormatados = ('0' + diferencaSegundos).slice(-2);

            return horasFormatadas + ':' + minutosFormatados + ':' + segundosFormatados;
        }

        // Função para atualizar o tempo decorrido a cada segundo
        function atualizarTempoDecorrido() {
            elementosTempoDecorrido.forEach(function(elemento) {
                var horaEntrada = elemento.parentNode.previousElementSibling.innerText;
                var tempoDecorrido = calcularTempoDecorrido(horaEntrada);
                elemento.innerText = tempoDecorrido;
            });
        }

        // Chama a função para atualizar o tempo decorrido a cada segundo
        setInterval(atualizarTempoDecorrido, 1000);
    });
    */
</script>


<script>
    $(document).ready(function(){

        function updateElapsedTime() {
        $('tr').each(function() {
            var horaEntrada = $(this).find('.hora-entrada').text();
            var horaSaida = $(this).find('.hora-saida').text();
            if (horaEntrada && horaSaida) {
                var partsEntrada = horaEntrada.split(':');
                var partsSaida = horaSaida.split(':');

                // Criar DateTime para a entrada e saída, usando a data atual para os componentes de data
                var now = new Date();
                var entradaDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), partsEntrada[0], partsEntrada[1], partsEntrada[2]);
                var saidaDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), partsSaida[0], partsSaida[1], partsSaida[2]);

                // Calcular o tempo decorrido
                var elapsed = new Date(now - entradaDate);

                var hours = String(elapsed.getUTCHours()).padStart(2, '0');
                var minutes = String(elapsed.getUTCMinutes()).padStart(2, '0');
                var seconds = String(elapsed.getUTCSeconds()).padStart(2, '0');

                var timeString = hours + ':' + minutes + ':' + seconds;
                $(this).find('.tdecorrido').text(timeString);

                // Verificar se o tempo atual excede a hora de saída
                if (now > saidaDate) {
                    $(this).addClass('expired');
                } else {
                    $(this).removeClass('expired');
                }
            }
        });
    }

    setInterval(updateElapsedTime, 1000);
    updateElapsedTime();

    function calcularPermanenciaEmMinutos(entradaTimestamp, saidaTimestamp) {
        // Calcula a diferença em segundos entre os dois timestamps
        const diferencaEmSegundos = saidaTimestamp - entradaTimestamp;
        
        // Converte a diferença de segundos para minutos
        const diferencaEmMinutos = diferencaEmSegundos / 60;
        
        // Retorna o resultado arredondado para o inteiro mais próximo
        return Math.round(diferencaEmMinutos);
    }


        $('.btnModalSaida').on('click', function(){
            let i = $(this).data('idprevenda');
            $('#modalSaida').modal();
            
            $.post("./blocos/busca-prevenda.php",{p:i}, function(data){
                let dados = JSON.parse(data);

                console.log(dados);
                $('#nomeresponsavel').html(dados[0]['responsavel']);
                $('#tel1').html(dados[0]['telefone1']);
                $('#tel2').html(dados[0]['telefone2']);
                $('#idprevenda').val(dados[0]['id_prevenda']);
                $('#tempo_agora').val(dados[0]['temponow']);
                
                // console.log(JSON.stringify(dados));

                $('#tabelaDados').empty();
            
                // Criar a tabela
                let tabela = $('<table>').addClass('table table-bordered m-b-0 table-hover');
                
                // Criar o cabeçalho da tabela
                let cabecalho = $('<thead>').append(
                    $('<tr>').append(
                        $('<th>').text('#'),
                        $('<th>').text('Nome'),
                        $('<th>').text('H.Entrada'),
                        $('<th>').text('H.Saída'),
                        $('<th>').text('Pacote'),
                        $('<th>').text('Permanência (min)')
                    )
                );
                
                // Adicionar cabeçalho à tabela
                tabela.append(cabecalho);

                // let total=0;
                
                // Criar o corpo da tabela
                let corpoTabela = $('<tbody>');
                
                // Iterar sobre os dados e adicionar cada linha à tabela
                dados.forEach(function(dado) {
                    let checkboxDiv   = $('<div>').addClass('checkbox');
                    let checkboxInput = $('<input>').attr('type', 'checkbox').addClass('checkbox chkmark').attr('name', 'chkvinculado[]').attr('id', 'checkbox_' + dado.id_entrada).attr('value', dado.id_vinculado).prop('checked', true);
                    let checkboxLabel  = $('<label>').attr('for', 'checkbox_' + dado.id_entrada);
                    
                    checkboxDiv.append(checkboxInput, checkboxLabel);
                    // let excedeLinha = 0;
                    // excedeLinha = dado.min_adicional * dado.tempoExcedenteMinutos;
                    // total = total + excedeLinha;

                    // let formattedValue = excedeLinha.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    let permanencia = calcularPermanenciaEmMinutos(dado.datahora_entra, dado.temponow);
                    
                    let linha = $('<tr>').append(            
                        $('<td>').append(checkboxDiv),
                        $('<td>').text(dado.nome),
                        $('<td>').text(dado.horaEntrada),
                        //$('<td>').text(new Date().getTime()),
                        $('<td>').text(dado.horaSaida),
                        $('<td>').text(dado.duracao),
                        $('<td>').text(permanencia)
                        
                    );
                    corpoTabela.append(linha);
                });
                
                // Adicionar corpo da tabela à tabela
                tabela.append(corpoTabela);
                
                // Adicionar a tabela à div desejada
                $('#tabelaDados').append(tabela);

            });  
                
        });

/*
        function convertTimestampToBRT(timestamp) {
            var date = new Date(timestamp * 1000); // Convert to milliseconds

            // Use toLocaleString to get the time in "America/Sao_Paulo" timezone
            var options = { 
                timeZone: 'America/Sao_Paulo', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                hour12: false 
            };
            
            return date.toLocaleString('pt-BR', options);
        }

        var timestamp = 1716235417;
        var brtTime = convertTimestampToBRT(timestamp);

        // $('#current-time').text(brtTime);
        console.log(brTime);
        */

        
    })
</script>

</body>
</html>
