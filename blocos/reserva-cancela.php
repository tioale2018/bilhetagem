<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
include_once('../inc/conexao.php');

session_start();

$id = $_POST['i'];
$sql = "update tbprevenda set prevenda_status=0 where id_prevenda=:id";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':id', $id, PDO::PARAM_INT);
$pre->execute();

session_destroy();

?>