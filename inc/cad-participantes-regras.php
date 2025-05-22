<?php
$hash = $_POST['hashevento'];
include_once("./inc/conexao.php");
include_once("./inc/funcoes.php");


require __DIR__ . '/../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

// Decodifica a senha criptografada
//cpf nome telefone email comunica

$encrypted_cpf      = base64_decode($_POST['cpf_seguro'] ?? '');
$encrypted_nome     = base64_decode($_POST['nome_seguro'] ?? '');
$encrypted_telefone = base64_decode($_POST['telefone_seguro'] ?? '');
$encrypted_email    = base64_decode($_POST['email_seguro'] ?? '');
$encrypted_comunica = base64_decode($_POST['comunica_seguro'] ?? '');



try {
    $cpf        = $privateKey->decrypt($encrypted_cpf);
    $nome       = $privateKey->decrypt($encrypted_nome);
    $telefone   = $privateKey->decrypt($encrypted_telefone);
    $email      = $privateKey->decrypt($encrypted_email);
    $comunica   = $privateKey->decrypt($encrypted_comunica);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

$telefone1 = $telefone;

//valida se é o hash do evento
$sql = "select tbevento_ativo.hash, tbevento_ativo.idevento, tbevento.titulo, tbevento.local, tbevento.modo_pgto, tbevento.regras_cadastro, tbevento.msg_fimreserva
from tbevento_ativo 
inner join tbevento on tbevento_ativo.idevento=tbevento.id_evento
where tbevento_ativo.ativo=1 and tbevento.status=2 and tbevento_ativo.hash=:hash";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':hash', $hash, PDO::PARAM_STR);
$pre->execute();

if ($pre->rowCount()<1) {
    header('Location: index.php');
    session_destroy();
} else {
    $row = $pre->fetchAll();
    $_SESSION['row']          = $row;
    $evento_atual             = $row[0]['idevento'];
    $_SESSION['evento_atual'] = $row[0]['idevento'];
    $_SESSION['hash_evento']  = $hash;
}

//---------------------------

$evento = $evento_atual;

//---------------------------------------------------------------------------------------------

//procedimento de busca dos pacotes deste evento
$sql_busca_pacote = "select * from tbpacotes where ativo=1 and id_evento=".$evento;
$pre_busca_pacote = $connPDO->prepare($sql_busca_pacote);
$pre_busca_pacote->execute();
$row_busca_pacote = $pre_busca_pacote->fetchAll();

$_SESSION['lista_pacotes'] = $row_busca_pacote;
//---------------------------------------------------------------------------------------------

//procediemnto de busca de tipo de vínculos
$sql_busca_vinculo = "select * from tbvinculo where ativo=1";
$pre_busca_vinculo = $connPDO->prepare($sql_busca_vinculo);
$pre_busca_vinculo->execute();
$row_busca_vinculo = $pre_busca_vinculo->fetchAll();

$_SESSION['lista_vinculos'] = $row_busca_vinculo;
//---------------------------------------------------------------------------------------------

//procedimento de busca dos perfis deste evento
$sql_busca_perfis = "select * from tbperfil_acesso where ativo=1 and idevento=".$evento;
$pre_busca_perfis = $connPDO->prepare($sql_busca_perfis);
$pre_busca_perfis->execute();
$row_busca_perfis = $pre_busca_perfis->fetchAll();

$_SESSION['lista_perfis'] = $row_busca_perfis;
$perfil_padrao = searchInMultidimensionalArray($_SESSION['lista_perfis'], 'padrao_evento', '1');

//---------------------------------------------------------------------------------------------

// $id               = limparCPF($_POST['cpf']);
$id               = limparCPF($cpf);
$cpf              = $id;
$_SESSION['cpf']  = $id;

$datahora         = time();
$hoje             = date('Y-m-d', $datahora);
$crianovaPrevenda = false;

$dados_responsavel = procuraResponsavel($id);

/*
//---------------------------------------------------
verifica se o responsável já possui pre venda ativa.
caso tenha, segue com ela.
caso não tenha, cria uma nova prevenda e segue com ela 
*/
//if ($pre_responsavel->rowCount()>0) {
if ($dados_responsavel!=false) {    
    // $dados_responsavel = $pre_responsavel->fetchAll();
    // verifica se tem pre-venda nao concluida
    $idResponsavel = $dados_responsavel[0]['id_responsavel'];

    $sql_busca_prevenda = "select * from tbprevenda where id_evento=$evento_atual and prevenda_status=9 and id_responsavel=$idResponsavel order by id_prevenda limit 1";
    $pre_busca_prevenda = $connPDO->prepare($sql_busca_prevenda);
    $pre_busca_prevenda->execute();

    if ($pre_busca_prevenda->rowCount()>0) {
        $row_busca_prevenda = $pre_busca_prevenda->fetchAll();
        $idPrevendaAtual = $row_busca_prevenda[0]['id_prevenda'];
    } else {
        $crianovaPrevenda = true;
    }

} else {
    //echo "0";
    //insere o responsavel
    $nome      = $nome; // $_POST['nome'];
    $email     = $email; // $_POST['email'];
    $telefone1 = $telefone1; // $_POST['telefone'];
    
    $sql_insere_responsavel = "insert into tbresponsavel (nome, cpf, email, telefone1, datahora_input) values (:nome, :cpf, :email, :telefone1, :datahora_input)";

    $pre_insere_responsavel = $connPDO->prepare($sql_insere_responsavel);

    $pre_insere_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':datahora_input', $datahora, PDO::PARAM_STR);

    $pre_insere_responsavel->execute();

    $idResponsavel = $connPDO->lastInsertId();

    $crianovaPrevenda = true;

    $dados_responsavel = procuraResponsavel($id);
}

//----------------------------------------------

if ($crianovaPrevenda) {
    $sql_prevenda = "insert into tbprevenda (id_responsavel, id_evento, data_acesso, prevenda_status, datahora_solicita) values (:id_responsavel, :id_evento, :data_acesso, 9, :datahora_solicita)";

    $pre_prevenda = $connPDO->prepare($sql_prevenda);

    $pre_prevenda->bindParam(':id_responsavel', $idResponsavel, PDO::PARAM_INT);
    $pre_prevenda->bindParam(':id_evento', $evento_atual, PDO::PARAM_INT);
    $pre_prevenda->bindParam(':data_acesso', $hoje, PDO::PARAM_STR);
    $pre_prevenda->bindParam(':datahora_solicita', $datahora, PDO::PARAM_STR);

    $pre_prevenda->execute();

    $idPrevendaAtual = $connPDO->lastInsertId();

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
            $sql_insere_vinculados = "insert into tbentrada (id_prevenda, id_vinculado, perfil_acesso) values (:idprevenda, :idvinculado, :perfilacesso)";
            $pre_insere_vinculados = $connPDO->prepare($sql_insere_vinculados);
            $pre_insere_vinculados->bindParam(':idprevenda', $idPrevendaAtual, PDO::PARAM_INT);
            $pre_insere_vinculados->bindParam(':idvinculado', $idVinculado, PDO::PARAM_INT);
            $pre_insere_vinculados->bindParam(':perfilacesso', $perfil_padrao['idperfil'], PDO::PARAM_INT);
            $pre_insere_vinculados->execute();
        }
    }
    
}
$_SESSION['idPrevenda']       = $idPrevendaAtual;
$_SESSION['dadosResponsavel'] = $dados_responsavel;




/* ************************************************************************ */

// $comunica = (isset($_POST['comunica'])) ? 1 : 0;

function getDeviceInfo($idprevenda) {
    // Informações adicionais coletadas pelo PHP
    $ipAddress = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
                 (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
    $serverLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Desconhecido', 0, 2);
    $serverDateTime = date('Y-m-d H:i:s');

    // Coleta de informações padrão via servidor
    $deviceInfo = [
        'idprevenda' => $idprevenda,
        'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido',
        'browserLanguage' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Desconhecido',
        'operatingSystem' => php_uname('s') . ' ' . php_uname('r'),
        'ipAddress' => $ipAddress,
        'timeZone' => date_default_timezone_get(),
        'serverLanguage' => $serverLanguage,
        'serverDateTime' => $serverDateTime,
        'connectionType' => 'Indisponível (via servidor)'
    ];

    return $deviceInfo;
}


$deviceInfo = getDeviceInfo($idPrevendaAtual);

$idprevenda       = $deviceInfo['idprevenda'];
$userAgent        = $deviceInfo['userAgent'];
$browserLanguage  = $deviceInfo['browserLanguage'];
$operatingSystem  = $deviceInfo['operatingSystem'];
$ipAddress        = $deviceInfo['ipAddress'];
$timeZone         = $deviceInfo['timeZone'];
$serverLanguage   = $deviceInfo['serverLanguage'];
$serverDateTime   = $deviceInfo['serverDateTime'];
$connectionType   = $deviceInfo['connectionType'];
$screenResolution = 'Desconhecido';
$deviceType       = 'Desconhecido';
$idTermoAtivo     = 0;



$stmt = $connPDO->prepare("INSERT INTO device_info (
    idprevenda, ip_address, user_agent, screen_resolution, device_type, 
    browser_language, server_language, operating_system, 
    time_zone, connection_type, server_date_time, created_at, termoativo, idresponsavel, precad
) VALUES (
    :idprevenda, :ipAddress, :userAgent, :screenResolution, :deviceType, 
    :browserLanguage, :serverLanguage, :operatingSystem, 
    :timeZone, :connectionType, :serverDateTime, NOW(), :idTermoAtivo, :idResponsavel, 1
)");



$stmt->execute([
    ':idprevenda' => $idprevenda,
    ':ipAddress' => $ipAddress,
    ':userAgent' => $userAgent,
    ':screenResolution' => $screenResolution,
    ':deviceType' => $deviceType,
    ':browserLanguage' => $browserLanguage,
    ':serverLanguage' => $serverLanguage,
    ':operatingSystem' => $operatingSystem,
    ':timeZone' => $timeZone,
    ':connectionType' => $connectionType,
    ':serverDateTime' => $serverDateTime,
    ':idTermoAtivo' => $idTermoAtivo,
    ':idResponsavel' => $idResponsavel
]);

$idDevice = $connPDO->lastInsertId();

$sql_atualizaResponsavel = "update tbresponsavel set comunica=$comunica, device_comunica=$idDevice where id_responsavel=$idResponsavel";
$pre_atualizaResponsavel = $connPDO->prepare($sql_atualizaResponsavel);
$pre_atualizaResponsavel->execute();


?>
