<?php 
require_once './inc/config_session.php';
if (!isset($_SESSION['user_id'])) {
    // Se não estiver definida, redireciona para a página de login

    header('Location: index.php');
    exit();
}
?>
<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Alpino Horizontal :: Locked</title>
<!-- Favicon-->
<link rel="icon" href="favicon.ico" type="image/x-icon">    
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">

<link rel="stylesheet" href="../assets/plugins/sweetalert/sweetalert.css"/>
<!-- Custom Css -->
<link rel="stylesheet" href="../assets/css/main.css">    
<link rel="stylesheet" href="../assets/css/color_skins.css">

</head>
<body class="theme-black">
<div class="authentication">
    <div class="container">
        <div class="row ">
            <div class="col-12">
                <div class="company_detail">
                        <h4 class="logo"><img src="assets/images/logo.svg" alt=""> Sistema de Gestão</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h3>Eventos disponíveis</h3>
                        <div class="body">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="">Cras justo odio</a></li>
                                <li class="list-group-item"><a href="">Cras justo odio</a></li>
                                <li class="list-group-item"><a href="">Cras justo odio</a></li>
                                
                            </ul>
                        </div>
                                              
                        
                    </div>                    
                </div>
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <h5>Michael</h5>
                            <span>info@example.com</span>
                        </div>
                        <div class="footer">
                            <a href="logoff.php" class="btn btn-primary btn-round btn-block logoff">LOGOFF</a>
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
        $('.logoff').click(function(e){
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