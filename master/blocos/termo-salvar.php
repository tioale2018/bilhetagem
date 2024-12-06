<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$termo    = $_POST['textotermo'];
$idtermo  = $_POST['idtermo'];
$cidade   = $_POST['cidade'];
$empresa  = $_POST['empresa'];
$cnpj     = $_POST['cnpj'];
$agora    = time();
$usuario  = $_SESSION['user_id'];

$sql = "update tbtermo set textotermo=:termo, dataatualiza=$agora, useratualiza=$usuario, cidadetermo=:cidade, empresa=:empresa, cnpj=:cnpj where idtermo=:idtermo";
// $sql = 'update tbtermo set textotermo="'.$termo.'", dataatualiza=$agora, useratualiza=$usuario, cidadetermo="$cidade" where idtermo=$idtermo';

$pre = $connPDO->prepare($sql);
$pre->bindParam(':termo', $termo, PDO::PARAM_STR);
$pre->bindParam(':idtermo', $idtermo, PDO::PARAM_INT);
$pre->bindParam(':cidade', $cidade, PDO::PARAM_STR);
$pre->bindParam(':empresa', $empresa, PDO::PARAM_STR);
$pre->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);

// die($sql);

if($pre->execute()) {
    echo json_encode(array('status' => '1'));
} else {
    echo json_encode(array('status' => '0'));
}

?>