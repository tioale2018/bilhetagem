<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$evento      = $_POST['evento'];
$urlhash     = $_POST['urlhash'];

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

$sql = "insert into tbevento_ativo (hash, idevento, datahora_cria) values (:hash, :idevento, :datahora_cria)";
$pre = $connPDO->prepare($sql);

$pre->bindParam(':hash', $urlhash, PDO::PARAM_STR);
$pre->bindParam(':idevento', $evento, PDO::PARAM_INT);
$pre->bindParam(':datahora_cria', $agora, PDO::PARAM_INT);
$pre->execute();


echo json_encode(array('status' => '1'));

?>