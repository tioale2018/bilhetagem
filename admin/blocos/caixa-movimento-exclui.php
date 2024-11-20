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
$pre->execute();






/* formulário do caixa, despesas  */



/*
$sql_despesas = "SELECT sum(tbcaixa_movimento.valor) as valortotal FROM tbcaixa_movimento WHERE tbcaixa_movimento.ativo=1 and tbcaixa_movimento.idcaixaabre=:idcaixaabre";
$pre_despesas = $connPDO->prepare($sql_despesas);
$pre_despesas->bindParam(':idcaixaabre', $idcaixaabre, PDO::PARAM_INT);
$pre_despesas->execute();
$dados_despesas = $pre_despesas->fetch(PDO::FETCH_ASSOC);
*/
$sql_despesas = "SELECT sum(valor) as valortotal, idcaixaabre FROM tbcaixa_movimento where ativo=1 and idcaixaabre=(select idcaixaabre from tbcaixa_movimento where id=:iditem)";
$pre_despesas = $connPDO->prepare($sql_despesas);
$pre_despesas->bindParam(':iditem', $iditem, PDO::PARAM_INT);
$pre_despesas->execute();
$dados_despesas = $pre_despesas->fetch(PDO::FETCH_ASSOC);


$total_despesas = $dados_despesas['valortotal'];


$sql_buscaformulario = "SELECT * FROM tbcaixa_formulario WHERE status>0 and idcaixadiario=".$dados_despesas['idcaixaabre'];
$pre_buscaformulario = $connPDO->prepare($sql_buscaformulario);
// $pre_buscaformulario->bindParam(':idcaixaabre', $idcaixaabre, PDO::PARAM_INT);
$pre_buscaformulario->execute();
$dados_formulario = $pre_buscaformulario->fetch(PDO::FETCH_ASSOC);

$idformulario_altera = $dados_formulario['id'];

$sql = "UPDATE tbcaixa_formulario SET val_despesas='$total_despesas' WHERE id = $idformulario_altera";
$pre = $connPDO->prepare($sql);
$pre->execute();


echo json_encode(array('status' => '1'));


?>