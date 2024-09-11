<?php
session_start();
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
include_once('../inc/funcoes.php');

$entrada = $_POST['e'];
$pacote  = $_POST['p'];


$sql_pacote = "select * from tbpacotes where ativo=1 and id_pacote=:pacote";
$pre_pacote = $connPDO->prepare($sql_pacote);
$pre_pacote->bindParam(':pacote', $pacote, PDO::PARAM_INT);
$pre_pacote->execute();
// $row_pacote = $pre_pacote->fetch(PDO::FETCH_ASSOC);
$row_pacote = $pre_pacote->fetchAll();
//não valido a existencia desse pacote

// var_dump($row_pacote);

$valor      = $row_pacote[0]['valor'];
$duracao    = $row_pacote[0]['duracao'];
$tolerancia = $row_pacote[0]['tolerancia'];
$adicional  = $row_pacote[0]['min_adicional'];
$descricao  = $row_pacote[0]['descricao'];

// $sql = "update tbentrada set id_pacote=:pacote where id_entrada=:entrada";
$sql = "update tbentrada set id_pacote=:pacote, pct_valor=:valor, pct_duracao=:duracao, pct_tolerancia=:tolerancia, pct_valor_adicional=:adicional, pct_nome=:descricao where id_entrada=:entrada";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':entrada', $entrada, PDO::PARAM_INT);
$pre->bindParam(':pacote', $pacote, PDO::PARAM_INT);
$pre->bindParam(':valor', $valor, PDO::PARAM_STR);
$pre->bindParam(':duracao', $duracao, PDO::PARAM_INT);
$pre->bindParam(':tolerancia', $tolerancia, PDO::PARAM_INT);
$pre->bindParam(':adicional', $adicional, PDO::PARAM_STR);
$pre->bindParam(':descricao', $descricao, PDO::PARAM_STR);
$pre->execute();

$datahora        = time();
$ipUsuario       = obterIP();

$sql_addlog = "insert into tbuserlog (idusuario, datahora, codigolog, ipusuario, acao) values (".$_SESSION['user_id'].", '$datahora', $entrada, '$ipUsuario', 'troca pacote id: $pacote, entrada: $entrada')";
$pre_addlog = $connPDO->prepare($sql_addlog);
$pre_addlog->execute();

?>