<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$sql = "insert into tbpacotes (descricao, id_evento) values ('alguma coisa', 9)";
$pre = $connPDO->prepare($sql);
// $pre->bindValue(':idevento', $id);
$pre->execute();

?>