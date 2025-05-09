<?php include_once('./inc/head.php') ?>

<?php include_once('./inc/conexao.php') ?>
<?php include_once('./inc/funcao-tempo.php') ?>
<?php include_once('./inc/funcoes-calculo.php') ?>
<?php include_once('./inc/funcoes.php') ?>


</head>
<body class="theme-black">
<?php //include_once('./inc/pageloader.php') ?>

<?php include_once('./inc/menu_topo.php') ?>
<?php include_once('./inc/menu_principal.php') ?>
<?php include_once('./inc/menu_lateral.php') ?>

<?php 
/*
base de consulta
select tbprevenda.id_prevenda, tbvinculados.nome as nomecrianca, tbresponsavel.nome as nomeresponsavel, tbprevenda.data_acesso, tbprevenda.datahora_efetiva ,tbprevenda.origem_prevenda, tbprevenda.prevenda_status from tbvinculados inner join tbentrada on tbentrada.id_vinculado = tbvinculados.id_vinculado inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel where tbresponsavel.cpf = "07316221704" and tbprevenda.id_evento=4 and tbprevenda.prevenda_status not in (0,9) and tbentrada.previnculo_status not in (0,2); 
*/

?>

<section class="content">    
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12 mt-4">
                    <h2>Exportação do termo de autorização</h2>             
                       
                </div>            
                <!-- <div class="col-lg-7 col-md-7 col-sm-12">
                    <ul class="breadcrumb float-md-right padding-0">
                        <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Basic Elements</li>
                    </ul>
                </div> -->
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card" id="details">
                    <div class="body">  
                    
                        
                        <div class="row mb-3">
                            <div class="col-md-12" >
                                <form action="" method="post" id="formBuscaEntradas"  class="mb-0 row align-items-center">
                                        <div class="col-md-2"><strong>Informe a busca:</strong></div> 
                                        <div class="col-md-10">
                                            <div class="row align-items-center">
                                                <div class="col-md-3">
                                                    <select class="form-control p-0" name="tipobusca" required>
                                                        <option value="cpf" selected>CPF</option>
                                                        <option value="nome">Nome participante</option>
                                                        <option value="ticket">Número do Ticket</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6"><input class="form-control" type="text" name="cpf" value="" required maxlength="14"></div>
                                                <div class="col-md-3"><button type="submit" class="btn btn-primary btn-round waves-effect buscaEntradasCpf">Buscar</button></div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            
                            <!-- <div class="col-md-6 col-sm-6 text-right">
                            <address>
                                    <strong>{responsável}</strong><br>
                                    {dado1}<br>
                                    {dado2}
                                </address>
                            </div> -->
                        </div>
                        <div class="mt-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="lista-entradas">

                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<?php include_once('./inc/javascript.php') ?>
<script src="./js/funcoes.js"></script>
<script>
    $(document).ready(function(){
        $('#formBuscaEntradas').submit(function(e){
            e.preventDefault();
            let cpf = $('input[name="cpf"]').val();
            let tipo = $('select[name="tipobusca"]').val();
            $('.lista-entradas').load('./blocos/termo-lista.php', {cpf: cpf, tipobusca: tipo}, function(){
                console.log(cpf);
            });
        });
/*
        $('input[name="cpf"]').on('input', function() {
            let cpf = $(this).val();
            $(this).val(aplicarMascaraCPF(cpf));
            
            // Validação do CPF
            if (!validarCPF(cpf.replace(/\D/g, ''))) {
                $(this).css('border', '2px solid red'); // Borda vermelha se o CPF for inválido
                $('button.buscaEntradasCpf').prop('disabled', true); // Impede o submit
            } else {
                $(this).css('border', ''); // Reseta a borda
                $('button.buscaEntradasCpf').prop('disabled', false); // Permite o submit
            }
        });

        $('input[name="cpf"]').on('focus', function() {
            $(this).css('border', '');
            let cpf = $(this).val().replace(/\D/g, '');
            $(this).val(aplicarMascaraCPF(cpf));
        });

        // Remove a máscara ao perder o foco
        $('input[name="cpf"]').on('blur', function() {
            let cpf = $(this).val().replace(/\D/g, '');
            $(this).val(cpf);
        });
        */
    });
</script>


</body>
</html>