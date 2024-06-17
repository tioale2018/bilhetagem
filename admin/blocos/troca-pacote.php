<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

if ( (!isset($_POST['e'])) || (!is_numeric($_POST['e'])) || (!isset($_POST['p'])) || (!is_numeric($_POST['p'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('../inc/conexao.php');

$entrada = $_POST['e'];
$pacote  = $_POST['p'];

$sql = "update tbentrada set id_pacote=:pacote where id_entrada=:entrada";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':entrada', $entrada, PDO::PARAM_INT);
$pre->bindParam(':pacote', $pacote, PDO::PARAM_INT);
$pre->execute();

?>