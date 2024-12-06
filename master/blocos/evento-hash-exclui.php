<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

// $evento      = $_POST['evento'];
$id     = $_POST['hash'];

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

$sql = "update tbevento_ativo set ativo=0 where id=:id";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':id', $id, PDO::PARAM_INT);
$pre->execute();

echo json_encode(array('status' => '1'));

?>