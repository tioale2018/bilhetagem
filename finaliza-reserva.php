<?php
if (($_SERVER['REQUEST_METHOD']!="POST") || (!isset($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

// Add CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

include('./inc/conexao.php');
$idPrevendaAtual   = htmlspecialchars($_POST['i'], ENT_QUOTES, 'utf-8');

$sql = "SELECT count(*) as total  FROM tbentrada WHERE id_prevenda=:idprevenda and previnculo_status=1 and ativo=1 and autoriza=0";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);

$pre->execute();
$row = $pre->fetchAll();
$total = $row[0]['total'];

if ($total>0) {
    echo json_encode(0);
} else {
    echo json_encode(1);
}
?>