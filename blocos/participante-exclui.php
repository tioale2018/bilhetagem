<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    
    die(0);
}
session_start();

include_once('../inc/conexao.php');

$entrada  = $_POST['i'];

$sql = "update tbentrada set ativo=0, previnculo_status=0 where id_entrada=:entrada";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':entrada', $entrada, PDO::PARAM_INT);

$pre->execute();


?>