<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>
<?php 



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
                    <div class="body" id="entrada-nova-lista">
                        
                    </div>
                </div>
            </div>
        </div> 
    </div>
</section>

<?php include('./inc/entrada-nova-modal.php') ?>
<?php include('./inc/javascript.php') ?>


<?php /* if ($_SESSION['evento']['tempo_atualiza']>0) { ?>
<script>
   // Função para recarregar a página
   function recarregarPagina() {
        location.reload();
    }
    setInterval(recarregarPagina, <?= $_SESSION['evento']['tempo_atualiza'] * 1000 ?> ); 
</script>
<?php } */ ?>
<script src="./js/safe.js"></script>
<script src="./js/operacional.js"></script>
<script>

$(document).ready(function(){
    $('#entrada-nova-lista').load('./blocos/entrada-nova-lista.php', { i: 1 });

    <?php if ($_SESSION['evento']['tempo_atualiza']>0) { ?>
    function recarregarPagina() {
        $('#entrada-nova-lista').load('./blocos/entrada-nova-lista.php', { i: 1 }, function(response, status, xhr) {
            if (status == "error") {
                window.location.reload();
            } else {
                try {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.error === 'session_expired') {
                        window.location.reload();
                    }
                } catch (e) {
                    // A resposta não é um JSON válido, continue normalmente
                }
            }
        });
    }
    setInterval(recarregarPagina, <?= $_SESSION['evento']['tempo_atualiza'] * 1000 ?>);
    <?php } ?>
})
</script>
</body>
</html>