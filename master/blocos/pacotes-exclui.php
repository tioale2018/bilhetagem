<?php 
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');
$id = $_POST['id'];

// $sql_buscapacotes = "SELECT * FROM tbpacotes WHERE ativo=1 and id_evento = :idevento order by descricao";
$sql_excluipacote = "update tbpacotes set ativo=0 where id_pacote=:idevento";
$pre_excluipacote = $connPDO->prepare($sql_excluipacote);
$pre_excluipacote->bindValue(':idevento', $id);
$pre_excluipacote->execute();
$row_excluipacote = $pre_excluipacote->fetchAll(PDO::FETCH_ASSOC);

if ($pre->execute()) {
    echo json_encode(array('status' => '1'));
} else {
    echo json_encode(array('status' => '0'));
}

?>