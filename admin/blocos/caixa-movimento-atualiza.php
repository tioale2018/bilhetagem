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


//recebe os dados de "caixa-movimento-modal.php"

$idevento      = $_SESSION['evento_selecionado'];
$datacaixa     = $_POST['datarelata'];
$tipomovimento = $_POST['tipomovimento'];
$valor         = str_replace(',', '.', str_replace('.', '', $_POST['valor']) );
$descricao     = $_POST['descricao'];
$item          = $_POST['item'];
$idusuario     = $_SESSION['user_id'];
$idcaixaabre   = $_POST['codcaixa'];

$sql = "insert into tbcaixa_movimento (idevento, idusuario, idtipodespesa, datahora_insercao, item, descricao, valor, datacaixa, idcaixaabre) values (:idevento, :idusuario, :idtipodespesa, :datahora_insercao, :item, :descricao, :valor, :datacaixa, :idcaixaabre)";
$pre = $connPDO->prepare($sql);

$pre->bindParam(':idevento', $idevento, PDO::PARAM_INT);
$pre->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$pre->bindParam(':idtipodespesa', $tipomovimento, PDO::PARAM_INT);
$pre->bindParam(':datahora_insercao', $horaagora, PDO::PARAM_INT);
$pre->bindParam(':item', $item, PDO::PARAM_INT);
$pre->bindParam(':descricao', $descricao, PDO::PARAM_STR);
$pre->bindParam(':valor', $valor, PDO::PARAM_STR);
$pre->bindParam(':datacaixa', $datacaixa, PDO::PARAM_STR);
$pre->bindParam(':idcaixaabre', $idcaixaabre, PDO::PARAM_INT);
$pre->execute();


/* formulário do caixa, despesas  */

$sql_despesas = "SELECT sum(tbcaixa_movimento.valor) as valortotal FROM tbcaixa_movimento WHERE tbcaixa_movimento.ativo=1 and tbcaixa_movimento.idcaixaabre=:idcaixaabre";
$pre_despesas = $connPDO->prepare($sql_despesas);
$pre_despesas->bindParam(':idcaixaabre', $idcaixaabre, PDO::PARAM_INT);
$pre_despesas->execute();
$dados_despesas = $pre_despesas->fetch(PDO::FETCH_ASSOC);

$total_despesas = $dados_despesas['valortotal'];


$sql_buscaformulario = "SELECT * FROM tbcaixa_formulario WHERE status>0 and idcaixadiario=:idcaixaabre";
$pre_buscaformulario = $connPDO->prepare($sql_buscaformulario);
$pre_buscaformulario->bindParam(':idcaixaabre', $idcaixaabre, PDO::PARAM_INT);
$pre_buscaformulario->execute();
$dados_formulario = $pre_buscaformulario->fetch(PDO::FETCH_ASSOC);

$idformulario_altera = $dados_formulario['id'];

$sql = "UPDATE tbcaixa_formulario SET val_despesas='$total_despesas' WHERE id = $idformulario_altera";
$pre = $connPDO->prepare($sql);
$pre->execute();


echo json_encode(array('status' => '1'));
exit;



?>