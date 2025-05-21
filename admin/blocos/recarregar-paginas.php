<?php
session_start();
/*
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
    */

include_once('../inc/conexao.php');
include_once('../inc/funcoes-gerais.php');

$datasHoje = geraDatasSQL(date('Y-m-d'));

/*
$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbpacotes.descricao as nomepacote, tbprevenda.prevenda_status
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3 and tbprevenda.prevenda_status in (2,5) and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.datahora_efetiva between ". $datasHoje['start'] ." and ". $datasHoje['end'] . " order by tbentrada.datahora_entra";
*/
$sql = "SELECT * FROM tbentrada
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
WHERE tbentrada.previnculo_status=3 and tbprevenda.prevenda_status in (2,5) and tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.datahora_efetiva between :datahojestart  and  :datahojeend";
// die($sql);
$dataHojeStart = $datasHoje['start'];
$dataHojeEnd   = $datasHoje['end'];

$pre = $connPDO->prepare($sql);
$pre->bindParam(':datahojestart', $dataHojeStart, PDO::PARAM_STR);
$pre->bindParam(':datahojeend', $dataHojeEnd, PDO::PARAM_STR);

$pre->execute();

$contador = $pre->rowCount();

echo json_encode(['valor' => $contador]);

?>

