<?php 
include_once('./inc/head.php');
include_once('./inc/conexao.php');
include_once('./inc/funcoes-gerais.php');
?>

<style>
    .expired {
        background-color: #f7a8ad;
    }

    .expired:hover {
        background-color: #C26765!important;
        color: #fff!important;
    }

    .expired:hover i{
        color: #fff!important;
    }

    .loader-aguarde {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0 , 0, 0.5);
        display: none;
        z-index: 9999999999;
    }
</style>

</head>
<body class="theme-black">
<?php include_once('./inc/pageloader.php') ?>

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
            </div>
        </div>
       
        <?php //include_once('./inc/cards-dashboard.php') ?>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card" id="controle-lista">
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once('./inc/controle-modal.php') ?>
<?php include_once('./inc/javascript.php') ?>
<script>
    $(document).ready(function(){
        $('#controle-lista').load('./blocos/controle-lista.php', {i:1});  
        
        <?php if ($_SESSION['evento']['tempo_atualiza']>0) { ?>
        
        function recarregarPagina() {
            $('#controle-lista').load('./blocos/controle-lista.php', { i: 1 }, function(response, status, xhr) {
                if (status == "error") {
                    window.location.reload();
                } else {
                    try {
                        let jsonResponse = JSON.parse(response);
                        if (jsonResponse.error === 'session_expired') {
                            window.location.reload();
                        }
                    } catch (e) {
                        // A resposta não é um JSON válido, continue normalmente
                    }
                }
            });
        }
        //setInterval(recarregarPagina, <?= $_SESSION['evento']['tempo_atualiza'] * 1000 ?>);
        var geral = 0;
        setInterval(() => {
            $.post('./blocos/recarregar-paginas.php', function(data){
                // console.log(data);
                let jsonResponse = JSON.parse(data);
                if (geral != jsonResponse.valor) {
                    geral = jsonResponse.valor;
                    recarregarPagina();
                }
            })
        }, <?= $_SESSION['evento']['tempo_atualiza'] * 1000 ?>);
        <?php } ?>

        
    })
     
</script>

</body>
</html>
