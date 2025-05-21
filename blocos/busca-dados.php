<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

if ( (!isset($_POST['cpf'])) ) {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$cpf = limparCPF($_POST['cpf']);

$sql = "select * from tbresponsavel where ativo=1 and cpf=:cpf";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$pre->execute();

$row = $pre->fetchAll();

echo json_encode($row);


?>