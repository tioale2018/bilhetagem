<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

if ( (!isset($_POST['cpf'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
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