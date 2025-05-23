<?php
// die(var_dump($_POST));
/*
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');


if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

include('../inc/conexao.php');
include('../inc/funcoes.php');

function dataParaMySQL($data) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return null; // Retorna null se não tiver 3 partes
}

//onde se lê "pacote" entenda perfil
// $nome          = $_POST['nome'];
// $nascimento    = convertDateToYMD($_POST['nascimento']);
$vinculo       = $_POST['vinculo'];
$perfil        = $_POST['pacote'];
// $idresponsavel = $_POST['idresponsavel'];
// $idprevenda    = $_POST['idprevenda'];

$encrypted_nome          = base64_decode($_POST['nome_seguro'] ?? '');
$encrypted_nascimento    = base64_decode($_POST['nascimento_seguro'] ?? '');
$encrypted_idresponsavel = base64_decode($_POST['idresponsavel_seguro'] ?? '');
$encrypted_idprevenda    = base64_decode($_POST['idprevenda_seguro'] ?? '');

try {
    $nome  = $privateKey->decrypt($encrypted_nome);
    $nascimento = $privateKey->decrypt($encrypted_nascimento);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);
    $idprevenda = $privateKey->decrypt($encrypted_idprevenda);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}


*/
// die(var_dump($_POST));

require '../../vendor/autoload.php';

use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(404);
    exit('Requisição inválida.');
}

include('../inc/conexao.php');
include('../inc/funcoes.php');

function dataParaMySQL($data) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return null; // Retorna null se não tiver 3 partes
}

// Carrega a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

try {
    $dados = [];

    // Detecta se é criptografia híbrida (campos 'dados_seguro', 'chave_segura' e 'iv' estão presentes)
    if (isset($_POST['dados_seguro'], $_POST['chave_segura'], $_POST['iv'])) {
        // Modo híbrido AES+RSA
        $dados_criptografados = base64_decode($_POST['dados_seguro']);
        $chave_criptografada = base64_decode($_POST['chave_segura']);
        $iv = base64_decode($_POST['iv']);

        // Descriptografa a chave AES com a chave RSA privada
        $chave_aes = $privateKey->decrypt($chave_criptografada);

        $aes = new AES('gcm');
        $aes->setKey($chave_aes);
        $aes->setIV($iv);
        $aes->setAAD('');
        $aes->setTagLength(16);

        $dados_json = $aes->decrypt($dados_criptografados);

        if (!$dados_json) {
            throw new Exception("Falha ao descriptografar os dados híbridos.");
        }

        $dados = json_decode($dados_json, true);

        // Adiciona campos não criptografados
        $dados['vinculo'] = $_POST['vinculo'] ?? '';
        $dados['pacote'] = $_POST['pacote'] ?? '';
    } else {
        // Modo RSA direto (campo a campo, padrão antigo)
        $camposEsperados = ['nome_seguro', 'nascimento_seguro', 'idresponsavel_seguro', 'idprevenda_seguro'];

        foreach ($camposEsperados as $campo) {
            if (isset($_POST[$campo])) {
                $decrypted = $privateKey->decrypt(base64_decode($_POST[$campo]));
                $chaveOriginal = str_replace('_seguro', '', $campo);
                $dados[$chaveOriginal] = $decrypted;
            }
        }

        // Adiciona campos não criptografados
        $dados['vinculo'] = $_POST['vinculo'] ?? '';
        $dados['pacote'] = $_POST['pacote'] ?? '';
    }

    // Agora os dados já estão disponíveis, seja qual for o formato de entrada
    $nome          = htmlspecialchars($dados['nome'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $nascimento    = dataParaMySQL($dados['nascimento'] ?? '');
    $idresponsavel = (int) ($dados['idresponsavel'] ?? 0);
    $idprevenda    = (int) ($dados['idprevenda'] ?? 0);
    $vinculo       = $dados['vinculo'] ?? '';
    $perfil        = $dados['pacote'] ?? '';

    // Aqui segue seu INSERT normalmente, usando as variáveis acima
    // Exemplo:
    // $sql = "INSERT INTO participantes (...) VALUES (...)";

} catch (Exception $e) {
    http_response_code(400);
    die("Erro ao processar os dados: " . $e->getMessage());
}

// die('aqui: ' . $nome);



// $nascimento = dataParaMySQL($nascimento);
$lembrar   = (isset($_POST['lembrarme'])?1:0);

//insere o vínculo
$sql_insere_vinculo = "insert into tbvinculados (id_responsavel, nome, nascimento, tipo, lembrar) values (:id_responsavel, :nome, :nascimento, :tipo, :lembrar)";
$pre_insere_vinculo = $connPDO->prepare($sql_insere_vinculo);
$pre_insere_vinculo->bindParam(':id_responsavel', $idresponsavel, PDO::PARAM_INT);
$pre_insere_vinculo->bindParam(':nome', $nome, PDO::PARAM_STR);
$pre_insere_vinculo->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
$pre_insere_vinculo->bindParam(':tipo', $vinculo, PDO::PARAM_INT);
$pre_insere_vinculo->bindParam(':lembrar', $lembrar, PDO::PARAM_INT);
$pre_insere_vinculo->execute();

$ultimo_id = $connPDO->lastInsertId();

$sql_insere_entrada = "insert into tbentrada (id_prevenda, id_vinculado, perfil_acesso) values (:id_prevenda, :id_vinculado, :perfil_acesso)";
$pre_insere_entrada = $connPDO->prepare($sql_insere_entrada);
$pre_insere_entrada->bindParam(':id_prevenda', $idprevenda, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':id_vinculado', $ultimo_id, PDO::PARAM_INT);
$pre_insere_entrada->bindParam(':perfil_acesso', $perfil, PDO::PARAM_INT);

$pre_insere_entrada->execute();

?>