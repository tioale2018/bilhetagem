<?php
require_once './inc/config_session.php';
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('./inc/conexao.php');
    // Aqui você faria a verificação do usuário (ex. consulta ao banco de dados)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Supondo que a verificação foi bem-sucedida e obteve o user_id do banco de dados
    // $sql_busca_user = "select * from tbusuarios where login=:login and senha=MD5(:senha)";
    $sql_busca_user = "SELECT tbusuarios.*, tbusuarios_evento.idevento
    FROM tbusuarios
    inner join tbusuarios_evento on tbusuarios.id_usuario=tbusuarios_evento.idusuario
    where tbusuarios_evento.ativo=1 and tbusuarios.ativo=1 and tbusuarios.login=:login and tbusuarios.senha=MD5(:senha)";
    $pre_busca_user = $connPDO->prepare($sql_busca_user);
    $pre_busca_user->bindParam(':login', $username, PDO::PARAM_STR);
    $pre_busca_user->bindParam(':senha', $password, PDO::PARAM_STR);
    $pre_busca_user->execute();

    if ($pre_busca_user->rowCount()==0) {
        $erro = "Login ou senha inválidos";
    } else {
        $row_busca_user = $pre_busca_user->fetchAll();
        // die(var_dump($row_busca_user));
        //die($row_busca_user[0]['nome']);
        // $user_id = $row_busca_user[0]['id_usuario']; // Exemplo de ID do usuário obtido após verificação


        // Define a variável de sessão
        $_SESSION['user_id'] = $row_busca_user[0]['id_usuario'];
        //$_SESSION['user_evento'] = $row_busca_user[0]['idevento']; 
        $_SESSION['user_perfil'] = $row_busca_user[0]['perfil']; 

        //rever isso no futuro
        $_SESSION['evento_selecionado'] = 0; //$_SESSION['user_evento'];

        // Redireciona para a página protegida
        header('Location: controle.php');
        exit();

    }
    
}
*/
if (isset($_SESSION['user_id'])) {
    // Se estiver definida, redireciona para a página do sistema
    header('Location: controle.php');
    exit();
}
?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>Sistema de Bilhetagem</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- Favicon-->
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/plugins/morrisjs/morris.css" />
<link rel="stylesheet" href="../assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css"/>
<!-- JQuery DataTable Css -->
<link rel="stylesheet" href="../assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="../assets/plugins/sweetalert/sweetalert.css"/>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="../assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

<!-- Bootstrap Select Css -->
<link href="../assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<!-- Custom Css -->
<link rel="stylesheet" href="../assets/css/main.css">
<link rel="stylesheet" href="../assets/css/color_skins.css">

<script src="../assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->
<style>
    .logo-brand {
        margin: 0 0 20px 0;
    }

    .logo-brand img {
        width: 250px;
        height: auto;
    }
</style>
</head>


<body class="theme-black">
<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="row">
                <div class="col-12">
                    <div class="company_detail">
                        <div class="logo-brand"><img src="./img/logo-multi2.png" alt=""></div>
                        <!-- <h4 class="logo"> </h4><img src="../assets/images/logo.svg" alt=""> Gestão -->
                    </div>
                </div>
            </div>
            <div class="row flex-md-row-reverse">
                
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <h5>Contato</h5>
                        </div>

                        <div class="body">
                            <p>Caso tenha esquecido seu login ou senha, entre em contato com o seu supervisor.</p>
                        </div>
                        
                        <a href="./index" class="link">Retornar</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        
                        <h3>Sistema de controle de acessos</h3>
                        <p>Esqueceu seu login ou senha? </p>
                        <div class="footer">
                            <ul  class="social_link list-unstyled">
                                <li><a href="https://www.linkedin.com/" title="LinkedIn"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://www.facebook.com/" title="Facebook"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="http://twitter.com/" title="Twitter"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="http://plus.google.com/" title="Google plus"><i class="zmdi zmdi-google-plus"></i></a></li>
                                
                            </ul>
                            <hr>
                            <ul>
                                <li><a href="" target="_blank">Contato</a></li>
                                <li><a href="" target="_blank">FAQ</a></li>
                            </ul>
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
</body>
</html>