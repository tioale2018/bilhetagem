<?php

if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

session_start();
// include_once('../inc/funcoes.php'); 
include_once("../inc/conexao.php");
$horaagora = time();
$idusuario = $_SESSION['user_id'];

// die(var_dump($_POST));

$iditem = $_POST['item'];
$sql    = "update tbcaixa_movimento set ativo=0, usuario_exclui=:idusuario, datahora_exclui=:horaagora where id=:iditem";
$pre    = $connPDO->prepare($sql);
$pre->bindParam(':iditem', $iditem, PDO::PARAM_INT);
$pre->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$pre->bindParam(':horaagora', $horaagora, PDO::PARAM_INT);

if ($pre->execute()) {
    echo json_encode(array('status' => '1'));
 
}   


?>