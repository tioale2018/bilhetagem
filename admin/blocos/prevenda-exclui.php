<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
include_once('../inc/conexao.php');
include_once('../inc/funcoes-gerais.php');
$idprevenda = intval($_POST['i']);

$sql_exclui_prevenda = "update tbprevenda set prevenda_status=0 where id_prevenda=:idprevenda";
$pre_exclui_prevenda = $connPDO->prepare($sql_exclui_prevenda);
$pre_exclui_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre_exclui_prevenda->execute();

?>