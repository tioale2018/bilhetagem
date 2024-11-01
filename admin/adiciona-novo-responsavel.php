<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
require_once './inc/config_session.php';
require_once './inc/functions.php';
require_once './inc/funcoes.php';

// Verifica a sessão
verificarSessao();
$ipUsuario = obterIP();

include_once('./inc/conexao.php');
$evento       = $_SESSION['evento_selecionado'];
$evento_atual = $evento;

$cpf       = limparCPF($_POST['cpf']);
$nome      = $_POST['nome'];
$telefone1 = $_POST['telefone1'];
$telefone2 = $_POST['telefone2'];
$email     = $_POST['email'];
$datahora  = time();
$hoje = date('Y-m-d', $datahora);

//----------------------------------------------------------------------------------------
// procedimento para encontrar os perfis disponiveis no evento
//utilizado para ser usado no perfil das crianças que forem lembrar=1 do responsável
$sql_busca_perfis = "select * from tbperfil_acesso where ativo=1 and idevento=".$evento;
$pre_busca_perfis = $connPDO->prepare($sql_busca_perfis);
$pre_busca_perfis->execute();
$row_busca_perfis = $pre_busca_perfis->fetchAll();
$_SESSION['lista_perfis'] = $row_busca_perfis;

//----------------------------------------------------------------------------------------

if ($_POST['idresponsavel']=='') {
    //insere o responsavel
    $sql_insere_responsavel = "insert into tbresponsavel (nome, cpf, email, telefone1, telefone2, datahora_input) values (:nome, :cpf, :email, :telefone1, :telefone2, :datahora_input)";

    $pre_insere_responsavel = $connPDO->prepare($sql_insere_responsavel);

    $pre_insere_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':telefone2', $telefone2, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':datahora_input', $datahora, PDO::PARAM_STR);
    $pre_insere_responsavel->execute();

    $ultimo_id_responsavel = $connPDO->lastInsertId();

    $sql_addlog = "insert into tbuserlog (idusuario, datahora, codigolog, ipusuario, acao) values (".$_SESSION['user_id'].", '$datahora', $ultimo_id_responsavel, '$ipUsuario', 'addresponsavel id $ultimo_id_responsavel')";
    $pre_addlog = $connPDO->prepare($sql_addlog);
    $pre_addlog->execute();   

} else {
    $ultimo_id_responsavel = intval(preg_replace('/[^0-9]/', '', $_POST['idresponsavel']));
}

$dados_responsavel     = procuraResponsavel($ultimo_id_responsavel);
$crianovaPrevenda      = false;

$idResponsavel = $dados_responsavel[0]['id_responsavel'];

$sql_busca_prevenda = "select * from tbprevenda where id_evento=$evento_atual and prevenda_status=1 and id_responsavel=$idResponsavel order by id_prevenda limit 1";
$pre_busca_prevenda = $connPDO->prepare($sql_busca_prevenda);
$pre_busca_prevenda->execute();

if ($pre_busca_prevenda->rowCount()>0) {
    $row_busca_prevenda = $pre_busca_prevenda->fetchAll();
    $idPrevendaAtual = $row_busca_prevenda[0]['id_prevenda'];
} else {
    $crianovaPrevenda = true;
}

if ($crianovaPrevenda) {
    $sql_prevenda = "insert into tbprevenda (id_responsavel, id_evento, data_acesso, prevenda_status, origem_prevenda, datahora_solicita, pre_reservadatahora) values (:id_responsavel, :id_evento, '$hoje', 1, 2, '$datahora', '$datahora')";
    // $sql_prevenda = "insert into tbprevenda (id_responsavel, id_evento, data_acesso, prevenda_status, datahora_efetiva, origem_prevenda) values (:id_responsavel, :id_evento, :data_acesso, 1, :datahora_efetiva, 2)";
    $pre_prevenda = $connPDO->prepare($sql_prevenda);
    $pre_prevenda->bindParam(':id_responsavel', $idResponsavel, PDO::PARAM_INT);
    $pre_prevenda->bindParam(':id_evento', $evento_atual, PDO::PARAM_INT);
    // $pre_prevenda->bindParam(':data_acesso', $hoje, PDO::PARAM_STR);
    // $pre_prevenda->bindParam(':datahora_efetiva', $datahora, PDO::PARAM_STR);
    $pre_prevenda->execute();

    $idPrevendaAtual = $connPDO->lastInsertId();

    $sql_addlog = "insert into tbuserlog (idusuario, datahora, codigolog, ipusuario, acao) values (".$_SESSION['user_id'].", '$datahora', $idPrevendaAtual, '$ipUsuario', 'addprevenda id: $idPrevendaAtual')";
    $pre_addlog = $connPDO->prepare($sql_addlog);
    $pre_addlog->execute();


    $perfil_padrao = searchInMultidimensionalArray($_SESSION['lista_perfis'], 'padrao_evento', '1');

    //procedimento de busca dos vinculados "lembrar" deste responsavel
    $sql_busca_vinculados = "select * from tbvinculados where lembrar=1 and id_responsavel=$idResponsavel";
    $pre_busca_vinculados = $connPDO->prepare($sql_busca_vinculados);
    $pre_busca_vinculados->execute();
    $row_busca_vinculados = $pre_busca_vinculados->fetchAll();

    //caso exista vinculados com o campo "lembrar=1" para este responsavel, insere na prevenda
    if ($pre_busca_vinculados->rowCount()>0) {
        foreach ($row_busca_vinculados as $key => $value) {
            $idVinculado = $row_busca_vinculados[$key]['id_vinculado'];
            $sql_insere_vinculados = "insert into tbentrada (id_prevenda, id_vinculado, perfil_acesso, autoriza, datahora_autoriza) values ($idPrevendaAtual, $idVinculado, ".$perfil_padrao['idperfil'].", 2, '$datahora')";
            $pre_insere_vinculados = $connPDO->prepare($sql_insere_vinculados);
            $pre_insere_vinculados->execute();
        }
    }
}

$ultimo_id_prevenda = $idPrevendaAtual;
header('Location: ./entrada-form?item='.$ultimo_id_prevenda);
?>