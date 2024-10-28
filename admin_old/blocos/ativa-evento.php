<?php
if ( $_SERVER['REQUEST_METHOD']!="POST" || checkPostVariable($_POST['i']) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

session_start();
/*
function isPostVariableInteger($varpost) {
    return is_numeric($varpost) && floor($varpost) == $varpost;
}

function isPostVariableInteger($varpost) {
    return is_numeric($varpost) && floor($varpost) == $varpost;
}
*/

function checkPostVariable($varpost) {
    if (isset($varpost)) {
        // Verifica se $_POST['i'] é um número (inteiro ou flutuante)
        if (is_numeric($varpost)) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

include_once("../inc/conexao.php");
$idevento = $_POST['i'];
$idUser   = $_SESSION['user_id'];

// $sql_eventos_usuario = "SELECT * FROM tbusuarios_evento where ativo=1 and idusuario=:iduser and idevento=:idevento";
/*
$sql_eventos_usuario = "SELECT tbusuarios_evento.*, tbevento.titulo, tbevento.cidade, tbevento.local, tbevento.capacidade, tbevento.tempo_atualiza FROM tbusuarios_evento
inner join tbevento on tbevento.id_evento=tbusuarios_evento.idevento
WHERE tbusuarios_evento.ativo=1 and tbusuarios_evento.idusuario=:iduser and tbevento.id_evento=:idevento";
*/
$sql_eventos_usuario = "SELECT * FROM tbusuarios_evento
inner join tbevento on tbevento.id_evento=tbusuarios_evento.idevento
WHERE tbusuarios_evento.ativo=1 and tbusuarios_evento.idusuario=:iduser and tbevento.id_evento=:idevento";

$pre_eventos_usuario = $connPDO->prepare($sql_eventos_usuario); 
$pre_eventos_usuario->bindParam(':iduser', $idUser, PDO::PARAM_INT);
$pre_eventos_usuario->bindParam(':idevento', $idevento, PDO::PARAM_INT);
$pre_eventos_usuario->execute();
$row_eventos_usuario = $pre_eventos_usuario->fetchAll();

// echo var_dump($pre_eventos_usuario->rowCount());

if ($pre_eventos_usuario->rowCount()>0) {
    $_SESSION['evento_selecionado'] = $_POST['i'];
    $_SESSION['evento_titulo']      = $row_eventos_usuario[0]['titulo'];
    $_SESSION['evento']             = $row_eventos_usuario[0];

} else {
    $_SESSION['evento_selecionado']=0;
}

?>