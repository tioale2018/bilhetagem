<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
include('../../admin/inc/conexao.php');

$regra_home     = $_POST['regra_home'];
$regra_cadastro = $_POST['regra_cadastro'];
$regra_parque   = $_POST['regra_parque'];
$regra_reserva  = $_POST['regra_reserva'];
$idevento       = $_POST['evento'];
$agora          = time();


$sql = "update tbevento set regras_home=:regra_home, regras_cadastro=:regra_cadastro, regras_parque=:regra_parque, msg_fimreserva=:regra_reserva, lastatualiza=$agora, useratualiza='".$_SESSION['user_id']."' where id_evento=:idevento";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':regra_home', $regra_home, PDO::PARAM_STR);
$pre->bindParam(':regra_cadastro', $regra_cadastro, PDO::PARAM_STR);
$pre->bindParam(':regra_parque', $regra_parque, PDO::PARAM_STR);
$pre->bindParam(':regra_reserva', $regra_reserva, PDO::PARAM_STR);
$pre->bindParam(':idevento', $idevento, PDO::PARAM_INT);
$pre->execute();

echo json_encode(array('status' => '1'));

?>