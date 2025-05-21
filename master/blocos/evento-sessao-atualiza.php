<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
include('../../admin/inc/conexao.php');

$evento      = $_POST['evento'];
$sessao      = $_POST['sessao'];
$mostratempo = $_POST['mostratempo'];
$atualiza    = $_POST['atualizalista'];
$usuario     = $_SESSION['user_id'];
/*
$titulo     = $_POST['titulo'];
$local      = $_POST['local'];
$cidade     = $_POST['cidade'];
$inicio     = strtotime($_POST['inicio']);
$fim        = strtotime($_POST['fim']);
$capacidade = $_POST['capacidade'];
$status     = $_POST['status'];
*/
$agora      = time();

$sql = "update tbevento set tempo_tela=:sessao, mostra_tempo=:mostratempo, tempo_atualiza=:atualiza, lastatualiza=:lastatualiza, useratualiza=:usuario where id_evento=:id";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':sessao', $sessao, PDO::PARAM_INT);
$pre->bindParam(':mostratempo', $mostratempo, PDO::PARAM_INT);
$pre->bindParam(':atualiza', $atualiza, PDO::PARAM_INT);
$pre->bindParam(':id', $evento, PDO::PARAM_INT);

$pre->bindParam(':lastatualiza', $agora, PDO::PARAM_INT);
$pre->bindParam(':usuario', $usuario, PDO::PARAM_INT);

$pre->execute();


echo json_encode(array('status' => '1'));

?>