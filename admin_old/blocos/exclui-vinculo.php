<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

if ( (!isset($_POST['e'])) || (!is_numeric($_POST['e'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$entrada = $_POST['e'];

$sql = "update tbentrada set previnculo_status=2 where id_entrada=:entrada";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':entrada', $entrada, PDO::PARAM_INT);
$pre->execute();

$datahora        = time();
$ipUsuario       = obterIP();

$sql_addlog = "insert into tbuserlog (idusuario, datahora, codigolog, ipusuario, acao) values (".$_SESSION['user_id'].", '$datahora', $entrada, '$ipUsuario', 'remove vinculo id: $entrada')";
$pre_addlog = $connPDO->prepare($sql_addlog);
$pre_addlog->execute();

?>