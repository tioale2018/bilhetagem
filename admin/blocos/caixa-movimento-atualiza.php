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
$valor         = str_replace(',', '.', $_POST['valor']);
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

if ($pre->execute()) {
    echo json_encode(array('status' => '1'));
    exit;
}


?>