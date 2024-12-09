<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$titulo     = $_POST['titulo'];
$local      = $_POST['local'];
$cidade     = $_POST['cidade'];
$inicio     = strtotime($_POST['inicio']);
$fim        = strtotime($_POST['fim']);
$capacidade = $_POST['capacidade'];
$url        = $_POST['url'];
$agora      = time();

$sql  = "insert into tbevento 
(titulo, local, cidade, capacidade, inicio, fim, status, regras_home, regras_cadastro, regras_parque, msg_fimreserva, tempo_atualiza, mostra_tempo, tempo_tela, lastatualiza, useratualiza ) 
select 
:titulo, :local, :cidade, :capacidade, :inicio, :fim, 1, t1.regras_home, t1.regras_cadastro, t1.regras_parque, t1.msg_fimreserva, t1.tempo_atualiza, t1.mostra_tempo, t1.tempo_tela, t1.lastatualiza, t1.useratualiza  
from tbevento as t1  
where t1.id_evento=1";

$pre = $connPDO->prepare($sql);

$pre->bindParam(':titulo', $titulo, PDO::PARAM_STR);
$pre->bindParam(':local', $local, PDO::PARAM_STR);
$pre->bindParam(':cidade', $cidade, PDO::PARAM_STR);
$pre->bindParam(':capacidade', $capacidade, PDO::PARAM_INT);
$pre->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$pre->bindParam(':fim', $fim, PDO::PARAM_INT);

if ($pre->execute()) {
    $ultimo_id = $connPDO->lastInsertId();
    echo json_encode(array('status' => '1', 'id' => $ultimo_id));
}

?>