<?php
include_once('../inc/funcoes.php'); 

if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('../inc/conexao.php');
include_once('../inc/caixa-salvar.php');

echo json_encode(array('status' => '1'));
exit;
?>