<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();
include_once('../inc/conexao.php');

$id           = $_POST['idprevenda'];
$horaAgora    = time();
$pgtoTipo     = $_POST['tipopgto'];
$pgtoValor    = $_POST['valorpgto'];
$pgtoDatahora = $_POST['horavenda'];

$sql = "update tbprevenda set prevenda_status=1, pre_pgtotipo=:pgtotipo, pre_pgtovalor=:pgtovalor, pre_pgtodatahora=:pgtodatahora where id_prevenda=:id";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':id', $id, PDO::PARAM_INT);
$pre->bindParam(':pgtotipo', $pgtoTipo, PDO::PARAM_INT);
$pre->bindParam(':pgtovalor', $pgtoValor, PDO::PARAM_STR);
$pre->bindParam(':pgtodatahora', $pgtoDatahora, PDO::PARAM_STR);

$pre->execute();

session_destroy();
?>