<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$id         = $_POST['evento'];
$descricao  = $_POST['descricao'];
$duracao    = $_POST['duracao'];
$tolerancia = $_POST['tolerancia'];
$valor      = str_replace(',', '.', str_replace('.', '', $_POST['valor']) ); //$_POST['valor'];
$adicional  = str_replace(',', '.', str_replace('.', '', $_POST['adicional']) ); //$_POST['adicional'];


$sql = "insert into tbpacotes (descricao, id_evento, valor, duracao, tolerancia, min_adicional) values (:descricao, :idevento, :valor, :duracao, :tolerancia, :adicional)";
$pre = $connPDO->prepare($sql);

$pre->bindParam(':descricao', $descricao, PDO::PARAM_STR);
$pre->bindParam(':idevento', $id, PDO::PARAM_INT);
$pre->bindParam(':valor', $valor, PDO::PARAM_STR);
$pre->bindParam(':duracao', $duracao, PDO::PARAM_INT);
$pre->bindParam(':tolerancia', $tolerancia, PDO::PARAM_INT);
$pre->bindParam(':adicional', $adicional, PDO::PARAM_STR);

if ($pre->execute()) {
    echo json_encode(array('status' => '1'));
} else {
    echo json_encode(array('status' => '0'));
}
//$pre->execute();

?>

