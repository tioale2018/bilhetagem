<?php
if (($_SERVER['REQUEST_METHOD']!="POST") || (!isset($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
include('./inc/conexao.php');
$idPrevendaAtual   = $_POST['i'];

$sql = "SELECT count(*) as total  FROM tbentrada WHERE id_prevenda=:idprevenda and previnculo_status=1 and ativo=1 and autoriza=0";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();
$total = $row[0]['total'];

if ($total>0) {
    echo json_encode(0);
} else {
    echo json_encode(1);
}
?>