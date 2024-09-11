<?php
if (($_SERVER['REQUEST_METHOD']!="POST") || (!isset($_POST['participante']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();
include('../inc/conexao.php');

$identrada = $_POST['participante'];
$horaagora = time();

$sql_atualiza_termo_participante = "update tbentrada set autoriza=1, datahora_autoriza='$horaagora' where id_entrada=:identrada";
$pre_atualiza_termo_participante = $connPDO->prepare($sql_atualiza_termo_participante);
$pre_atualiza_termo_participante->bindParam(':identrada', $identrada, PDO::PARAM_INT);
$pre_atualiza_termo_participante->execute();

?>