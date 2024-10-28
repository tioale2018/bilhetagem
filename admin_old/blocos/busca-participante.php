<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

if ( (!isset($_POST['p'])) || (!is_numeric($_POST['p'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('../inc/conexao.php');

$participante = intval($_POST['p']);
$prevenda     = intval($_POST['e']);

// $sql = "update tbentrada set previnculo_status=2 where id_entrada=:entrada";
//$sql = "select * from tbvinculados where id_vinculado=:vinculado";
$sql = "select tbvinculados.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbentrada.id_pacote 
from tbvinculados
inner join tbentrada on tbvinculados.id_vinculado=tbentrada.id_vinculado
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:prevenda and tbentrada.id_vinculado=:participante";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':participante', $participante, PDO::PARAM_INT);
$pre->bindParam(':prevenda', $prevenda, PDO::PARAM_INT);
$pre->execute();

$row = $pre->fetchAll();

echo json_encode($row);


?>