<?php 
require_once './inc/config_session.php';
require_once './inc/conexao.php';

if (isset($_SESSION['user_perfil']) && $_SESSION['user_perfil']==2)  {
    // Se não estiver definida, redireciona para a página de login
    session_unset();
    session_destroy();
    header('Location: /admin');
    exit();
}


if ( (!isset($_SESSION['user_id'])) ) {
    // Se não estiver definida, redireciona para a página de login
    session_unset();
    session_destroy();
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

<title>Sistema de Bilhetagem (Bloqueado)</title>
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
                        <h4 class="logo"><img src="../assets/images/logo.svg" alt=""> Sistema de Bilhetagem</h4>
                        <h3>Eventos disponíveis</h3>
                        <div class="body">
                            <?php
                            if ($pre_eventos_usuario->rowCount()>0) {
                                $row_eventos_usuario = $pre_eventos_usuario->fetchAll();
                            ?>
                            <ul class="list-group" style="max-height: 300px; overflow-y: auto">
                                
                                <?php foreach ($row_eventos_usuario as $key => $value) { ?>
                                    <li class="list-group-item"><a href="" class="sel-evento" data-idevento="<?= $value['idevento'] ?>"><?= $value['titulo'] . " (" . $value['cidade'] . ")"; ?></a></li>
                                <?php } ?>
                                
                            </ul>

                            <?php } else { ?>
                                <p class="" style="color: tomato">Nenhum evento cadastrado para este usuário</p>
                            <?php } ?>

                        </div>
                        
                    </div>                    
                </div>
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <img src="../assets/images/user.png" class="rounded-circle" alt="User">
                            <h5><?= $_SESSION['user_nome'] ?></h5>
                            <span><?= $_SESSION['user_login'] ?></span>
                        </div>
                        
                        <div class="footer">
                            <a href="" class="btn btn-primary btn-round btn-block logoff">Logoff</a>                            
                        </div>
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
                cancelButtonText: "Não",
                closeOnConfirm: false
            }, function () {
                location.href="logoff.php";
            })
        })
    })
</script>
</body>
</html>