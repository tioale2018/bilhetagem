<?php
// die(var_dump($_POST));
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;


if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

require_once './inc/config_session.php';
require_once './inc/functions.php';
require_once './inc/funcoes.php';

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');


// $cpf       = limparCPF($_POST['cpf']);
// $nome      = htmlspecialchars($_POST['nome'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
// $telefone1 = htmlspecialchars($_POST['telefone1'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
// $telefone2 = htmlspecialchars($_POST['telefone2'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
// $email     = htmlspecialchars($_POST['email'], ENT_QUOTES | ENT_HTML5, 'UTF-8');


// Decodifica a senha criptografada
$encrypted_cpf      = base64_decode($_POST['cpf_seguro'] ?? '');
$encrypted_nome     = base64_decode($_POST['nome_seguro'] ?? '');
$encrypted_telefone1= base64_decode($_POST['telefone1_seguro'] ?? '');
$encrypted_telefone2= base64_decode($_POST['telefone2_seguro'] ?? '');
$encrypted_email    = base64_decode($_POST['email_seguro'] ?? '');
$encrypted_idResponsavel = base64_decode($_POST['idresponsavel_seguro'] ?? '');

try {
    $cpf        = limparCPF($privateKey->decrypt($encrypted_cpf));
    // $nome       = $privateKey->decrypt($encrypted_nome);
    // $telefone1  = $privateKey->decrypt($encrypted_telefone1);
    // $telefone2  = $privateKey->decrypt($encrypted_telefone2);
    // $email      = $privateKey->decrypt($encrypted_email);
    // $idResponsavel = $privateKey->decrypt($encrypted_idResponsavel);
    $nome      = htmlspecialchars($privateKey->decrypt($encrypted_nome), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $telefone1 = htmlspecialchars($privateKey->decrypt($encrypted_telefone1), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $telefone2 = htmlspecialchars($privateKey->decrypt($encrypted_telefone2), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $email     = htmlspecialchars($privateKey->decrypt($encrypted_email), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $idResponsavel = htmlspecialchars($privateKey->decrypt($encrypted_idResponsavel), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}


// Verifica a sessão
verificarSessao();
$ipUsuario = obterIP();

include_once('./inc/conexao.php');
$evento       = $_SESSION['evento_selecionado'];
$evento_atual = $evento;

// $cpf       = limparCPF($_POST['cpf']);
// $nome      = htmlspecialchars($_POST['nome'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
// $telefone1 = htmlspecialchars($_POST['telefone1'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
// $telefone2 = htmlspecialchars($_POST['telefone2'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
// $email     = htmlspecialchars($_POST['email'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
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

if ($idResponsavel=='') {
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

    $sql_addlog = "insert into tbuserlog (idusuario, datahora, codigolog, ipusuario, acao) values (:userid, :datahora, :codigolog, :ipusuario, :acao)";
    $pre_addlog = $connPDO->prepare($sql_addlog);
    $pre_addlog->bindParam(':userid', $_SESSION['user_id'], PDO::PARAM_INT);
    $pre_addlog->bindParam(':datahora', $datahora, PDO::PARAM_INT);
    $pre_addlog->bindParam(':codigolog', $ultimo_id_responsavel, PDO::PARAM_INT);
    $pre_addlog->bindParam(':ipusuario', $ipUsuario, PDO::PARAM_STR);
    $pre_addlog->bindParam(':acao', 'addresponsavel id ' . $ultimo_id_responsavel, PDO::PARAM_STR);
    $pre_addlog->execute();   

} else {
    $ultimo_id_responsavel = intval(preg_replace('/[^0-9]/', '', $idResponsavel));
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

    $stmt = $connPDO->prepare("insert into tbuserlog (idusuario, datahora, codigolog, ipusuario, acao) values (:idusuario, :datahora, :codigolog, :ipusuario, :acao)");
    $stmt->bindParam(':idusuario', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':datahora', $datahora, PDO::PARAM_STR);
    $stmt->bindParam(':codigolog', $idPrevendaAtual, PDO::PARAM_INT);
    $stmt->bindParam(':ipusuario', $ipUsuario, PDO::PARAM_STR);
    $stmt->bindParam(':acao', 'addprevenda id: ' . $idPrevendaAtual, PDO::PARAM_STR);
    $stmt->execute();


    $perfil_padrao = searchInMultidimensionalArray($_SESSION['lista_perfis'], 'padrao_evento', '1');

    //procedimento de busca dos vinculados "lembrar" deste responsavel
    $stmt = $connPDO->prepare("select * from tbvinculados where lembrar=1 and id_responsavel=:id_responsavel");
    $stmt->bindParam(':id_responsavel', $idResponsavel, PDO::PARAM_INT);
    $stmt->execute();
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