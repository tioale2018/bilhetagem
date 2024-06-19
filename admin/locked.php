<?php 
require_once './inc/config_session.php';
require_once './inc/conexao.php';
if (!isset($_SESSION['user_id'])) {
    // Se não estiver definida, redireciona para a página de login

    header('Location: index.php');
    exit();
}

if ($_SESSION['evento_selecionado']!=0) {
    header('Location: controle.php');
}

$idUser = $_SESSION['user_id'];
$sql_eventos_usuario = "SELECT tbusuarios_evento.*, tbevento.titulo, tbevento.cidade, tbevento.local FROM tbusuarios_evento
inner join tbevento on tbevento.id_evento=tbusuarios_evento.idevento
WHERE tbusuarios_evento.ativo=1 and tbusuarios_evento.idusuario=:iduser";
$pre_eventos_usuario = $connPDO->prepare($sql_eventos_usuario);
$pre_eventos_usuario->bindParam(':iduser', $idUser, PDO::PARAM_STR);
$pre_eventos_usuario->execute();


?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Alpino Horizontal :: Locked</title>
<!-- Favicon-->
<link rel="icon" href="favicon.ico" type="image/x-icon">    
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">

<!-- Custom Css -->
<link rel="stylesheet" href="../assets/css/main.css">    
<link rel="stylesheet" href="../assets/css/color_skins.css">

<link rel="stylesheet" href="../assets/plugins/sweetalert/sweetalert.css"/>
</head>
<body class="theme-black">
<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h4 class="logo"><img src="../assets/images/logo.svg" alt=""> Alpino</h4>
                        <h3>Eventos disponíveis</h3>
                        <div class="body">
                            <?php
                            if ($pre_eventos_usuario->rowCount()>0) {
                                $row_eventos_usuario = $pre_eventos_usuario->fetchAll();
                            ?>
                            <ul class="list-group">
                                <?php foreach ($row_eventos_usuario as $key => $value) { ?>
                                    <li class="list-group-item"><a href="" class="sel-evento" data-idevento="<?= $value['idevento'] ?>"><?= $value['titulo'] . " (" . $value['cidade'] . ")"; ?></a></li>
                                <?php } ?>
                                
                            </ul>

                            <?php } else { ?>
                                <p class="" style="color: tomato">Nenhum evento cadastrado para este usuário</p>
                            <?php } ?>

                        </div>
                        
                        <div class="footer">
                            <ul  class="social_link list-unstyled">
                                <li><a href="https://thememakker.com" title="ThemeMakker"><i class="zmdi zmdi-globe"></i></a></li>
                                <li><a href="https://themeforest.net/user/thememakker" title="Themeforest"><i class="zmdi zmdi-shield-check"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/thememakker/" title="LinkedIn"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://www.facebook.com/thememakkerteam" title="Facebook"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="http://twitter.com/thememakker" title="Twitter"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="http://plus.google.com/+thememakker" title="Google plus"><i class="zmdi zmdi-google-plus"></i></a></li>
                                <li><a href="https://www.behance.net/thememakker" title="Behance"><i class="zmdi zmdi-behance"></i></a></li>
                            </ul>
                            <hr>
                            <ul>
                                <li><a href="http://thememakker.com/contact/" target="_blank">Contact Us</a></li>
                                <li><a href="http://thememakker.com/about/" target="_blank">About Us</a></li>
                                <li><a href="http://thememakker.com/services/" target="_blank">Services</a></li>
                                <li><a href="javascript:void(0);">FAQ</a></li>
                            </ul>
                        </div>
                    </div>                    
                </div>
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <img src="../assets/images/profile_av.jpg" class="rounded-circle" alt="User">
                            <h5>Michael</h5>
                            <span>info@example.com</span>
                        </div>
                        <form class="form">                            
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Enter your Password">
                                <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                            </div>
                        </form>
                        <div class="footer">
                            <a href="" class="btn btn-primary btn-round btn-block logoff">Logoff</a>                            
                        </div>
                        <a href="javascript:void(0);" class="link">Need Help?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jquery Core Js -->
<script src="../assets/bundles/libscripts.bundle.js"></script>
<script src="../assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 
<script src="../assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
<script>
       $(document).ready(function(){
        $('.sel-evento').on('click', function(e){
            e.preventDefault();
            let i = $(this).data('idevento');
            $.post('./blocos/ativa-evento.php', {i:i}, function() {
                location.reload();
            })
        })

        $('.logoff').on('click', function(e){
            e.preventDefault();
            swal({
                title: "Logoff?",
                text: "Deseja realizar o logoff?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim",
                closeOnConfirm: false
            }, function () {
                location.href="logoff.php";
            })
        })
    })
</script>
</body>
</html>