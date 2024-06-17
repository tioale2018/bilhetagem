<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
require_once '../inc/config_session.php';
require_once '../inc/functions.php';
    // Verifica a sessão
    verificarSessao();

include_once('../inc/conexao.php');
$evento = $_SESSION['evento_selecionado'];


$cpf       = $_POST['cpf'];
$nome      = $_POST['nome'];
$telefone1 = $_POST['telefone1'];
$telefone2 = $_POST['telefone2'];
$email     = $_POST['email'];
$datahora  = time();

if ($_POST['idresponsavel']=='') {
    //insere o responsavel
    $sql_insere_responsavel = "insert into tbresponsavel (nome, cpf, email, telefone1, telefone2, datahora_input) values (:nome, :cpf, :email, :telefone1, :telefone2, :datahora_input)";

    $pre_insere_responsavel = $connPDO->prepare($sql_insere_responsavel);

    $pre_insere_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_INT);
    $pre_insere_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':telefone2', $telefone2, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':datahora_input', $datahora, PDO::PARAM_STR);

    $pre_insere_responsavel->execute();

    $ultimo_id_responsavel = $connPDO->lastInsertId();
} else {
    // die('busca existente');
    $ultimo_id_responsavel = $_POST['idresponsavel'];

    $sql_verifica_prevenda = "select * from tbprevenda where prevenda_status=1 and id_responsavel=:id order by id_prevenda desc limit 1";
    $pre_verifica_prevenda = $connPDO->prepare($sql_verifica_prevenda);
    $pre_verifica_prevenda->bindParam(':id', $ultimo_id_responsavel, PDO::PARAM_INT);
    $pre_verifica_prevenda->execute();

    if ($pre_verifica_prevenda->rowCount()>0) {
        $row_prevenda = $pre_verifica_prevenda->fetchAll();

        $ultimo_id_prevenda = $row_prevenda[0]['id_prevenda'];
    } else {

        $hoje = date('Y-m-d', $datahora);

        $sql_prevenda = "insert into tbprevenda (id_responsavel, id_evento, data_acesso, prevenda_status, datahora_solicita) values (:id_responsavel, :id_evento, :data_acesso, 1, :datahora_solicita)";

        $pre_prevenda = $connPDO->prepare($sql_prevenda);

        $pre_prevenda->bindParam(':id_responsavel', $ultimo_id_responsavel, PDO::PARAM_INT);
        $pre_prevenda->bindParam(':id_evento', $evento, PDO::PARAM_INT);
        $pre_prevenda->bindParam(':data_acesso', $hoje, PDO::PARAM_STR);
        $pre_prevenda->bindParam(':datahora_solicita', $datahora, PDO::PARAM_STR);

        $pre_prevenda->execute();

        $ultimo_id_prevenda = $connPDO->lastInsertId();

    }

}

header('Location: ../entrada-form.php?item='.$ultimo_id_prevenda);
?>