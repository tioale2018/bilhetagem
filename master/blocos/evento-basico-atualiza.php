<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$evento     = $_POST['evento'];
$titulo     = $_POST['titulo'];
$local      = $_POST['local'];
$cidade     = $_POST['cidade'];
$inicio     = strtotime($_POST['inicio']);
$fim        = strtotime($_POST['fim']);
$capacidade = $_POST['capacidade'];
$status     = $_POST['status'];
$agora      = time();

$sql = "update tbevento set titulo=:titulo, local=:local, cidade=:cidade, inicio=:inicio, fim=:fim, capacidade=:capacidade, status=:status, lastatualiza=:lastatualiza where id_evento=:id";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':titulo', $titulo, PDO::PARAM_STR);
$pre->bindParam(':local', $local, PDO::PARAM_STR);
$pre->bindParam(':cidade', $cidade, PDO::PARAM_STR);
$pre->bindParam(':inicio', $inicio, PDO::PARAM_STR);
$pre->bindParam(':fim', $fim, PDO::PARAM_STR);
$pre->bindParam(':capacidade', $capacidade, PDO::PARAM_INT);
$pre->bindParam(':status', $status, PDO::PARAM_INT);
$pre->bindParam(':lastatualiza', $agora, PDO::PARAM_INT);
$pre->bindParam(':id', $evento, PDO::PARAM_INT);
$pre->execute();


echo json_encode(array('status' => '1'));

?>