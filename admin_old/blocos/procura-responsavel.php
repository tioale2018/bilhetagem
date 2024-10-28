<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
function limparCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    return $cpf;
}

include_once('../inc/conexao.php');

$id = limparCPF($_POST['id']);

$sql_responsavel = "select * from tbresponsavel where cpf=:cpf";
$pre_responsavel = $connPDO->prepare($sql_responsavel);
$pre_responsavel->bindParam(':cpf', $id, PDO::PARAM_INT);
$pre_responsavel->execute();

if ($pre_responsavel->rowCount()>0) {
    $dados_responsavel = $pre_responsavel->fetchAll();
    echo json_encode($dados_responsavel);
} else {
    echo "0";
}

?>