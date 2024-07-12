<?php
// Inclui o arquivo de configuração de sessão
require_once './inc/config_session.php';
require_once './inc/functions.php';
    // Verifica a sessão
verificarSessao();
    /*
echo basename($_SERVER['PHP_SELF']);
if (!basename($_SERVER['PHP_SELF'])=='index.php') {
    # code...
    // Inclui o arquivo de funções
    
} ;
*/

?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
    <meta name="robots" content="noindex, nofollow">

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