<?php
// echo strlen($_POST['nome']) . " chars in nome\n";
// echo "<hr>";
// die(var_dump($_POST));

// require '../../vendor/autoload.php';

// use phpseclib3\Crypt\RSA;
// use phpseclib3\Crypt\PublicKeyLoader;

// // Lê a chave privada
// $privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
//     ->withPadding(RSA::ENCRYPTION_OAEP)
//     ->withHash('sha256');




/*
// Decodifica a senha criptografada
if (isset($_POST['id_prevenda_seguro'])) {
    $encrypted_i      = base64_decode($_POST['id_prevenda_seguro'] ?? '');
} else {
    $encrypted_i      = base64_decode($_POST['i'] ?? '');
}
*/

// $nome          = $data['nome'] ?? '';
// $nascimento    = $data['nascimento'] ?? '';
// $vinculo       = $data['vinculo'] ?? '';
// $pacote        = $data['pacote'] ?? '';
// $idresponsavel = $data['idresponsavel'] ?? '';
// $idprevenda    = $data['idprevenda'] ?? '';
// $idvinculado   = $data['idvinculado'] ?? '';
// $identrada     = $data['identrada'] ?? '';

/*
$encrypted_nome      = base64_decode($_POST['nome'] ?? '');
$encrypted_nascimento = base64_decode($_POST['nascimento'] ?? '');
$encrypted_vinculo   = base64_decode($_POST['vinculo'] ?? '');
$encrypted_pacote    = base64_decode($_POST['pacote'] ?? '');
$encrypted_idresponsavel = base64_decode($_POST['idresponsavel'] ?? '');
$encrypted_idprevenda    = base64_decode($_POST['idprevenda'] ?? '');
$encrypted_idvinculado   = base64_decode($_POST['idvinculado'] ?? '');
$encrypted_identrada     = base64_decode($_POST['identrada'] ?? '');

// $idprevenda = intval($_POST['i']);

try {
    $nome          = $privateKey->decrypt($encrypted_nome);
    $nascimento    = $privateKey->decrypt($encrypted_nascimento);
    $vinculo       = $privateKey->decrypt($encrypted_vinculo);
    $pacote        = $privateKey->decrypt($encrypted_pacote);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);
    $idprevenda    = $privateKey->decrypt($encrypted_idprevenda);
    $idvinculado   = $privateKey->decrypt($encrypted_idvinculado);
    $identrada     = $privateKey->decrypt($encrypted_identrada);

    
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

*/
/*

function safeBase64Decode($field) {
    $val = $_POST[$field] ?? '';
    $decoded = base64_decode($val, true);
    if ($decoded === false) {
        throw new Exception("Campo '$field' inválido ou não está em base64.");
    }
    return $decoded;
}

try {
    $encrypted_nome        = safeBase64Decode('nome');
    $encrypted_nascimento  = safeBase64Decode('nascimento');
    $encrypted_vinculo     = safeBase64Decode('vinculo');
    $encrypted_pacote      = safeBase64Decode('pacote');
    $encrypted_idresponsavel = safeBase64Decode('idresponsavel');
    $encrypted_idprevenda    = safeBase64Decode('idprevenda');
    $encrypted_idvinculado   = safeBase64Decode('idvinculado');
    $encrypted_identrada     = safeBase64Decode('identrada');

    $nome          = $privateKey->decrypt($encrypted_nome);
    $nascimento    = $privateKey->decrypt($encrypted_nascimento);
    $vinculo       = $privateKey->decrypt($encrypted_vinculo);
    $pacote        = $privateKey->decrypt($encrypted_pacote);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);
    $idprevenda    = $privateKey->decrypt($encrypted_idprevenda);
    $idvinculado   = $privateKey->decrypt($encrypted_idvinculado);
    $identrada     = $privateKey->decrypt($encrypted_identrada);

} catch (Exception $e) {
    die("Erro ao descriptografar: " . $e->getMessage());
}

*/


/*

// Recebe os dados JSON da requisição
$input = json_decode(file_get_contents('php://input'), true);

// Verifica se os campos necessários estão presentes
if (!isset($input['key']) || !isset($input['iv']) || !isset($input['ciphertext']) || !isset($input['tag'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados incompletos para descriptografia.']);
    exit;
}

try {
    // Descriptografa a chave AES usando a chave privada RSA
    $aesKey = $privateKey->decrypt(base64_decode($input['key']));

    // Inicializa o AES em modo GCM
    $aes = new AES('gcm');
    $aes->setKey($aesKey);
    $aes->setIV(base64_decode($input['iv']));
    $aes->setTag(base64_decode($input['tag']));
    $aes->setAAD(''); // Opcional

    // Descriptografa os dados
    $decryptedJson = $aes->decrypt(base64_decode($input['ciphertext']));

    if (!$decryptedJson) {
        throw new Exception('Falha na descriptografia AES.');
    }

    // Converte o JSON para array
    $data = json_decode($decryptedJson, true);
    if (!is_array($data)) {
        throw new Exception('JSON inválido após descriptografia.');
    }

    // Agora você pode acessar os dados normalmente
    $nome          = $data['nome'] ?? '';
    $nascimento    = $data['nascimento'] ?? '';
    $vinculo       = $data['vinculo'] ?? '';
    $pacote        = $data['pacote'] ?? '';
    $idresponsavel = $data['idresponsavel'] ?? '';
    $idprevenda    = $data['idprevenda'] ?? '';
    $idvinculado   = $data['idvinculado'] ?? '';
    $identrada     = $data['identrada'] ?? '';

    // (Opcional) Processa os dados como quiser aqui...

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao descriptografar: ' . $e->getMessage()]);
    exit;
}
*/


require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');


function dataParaMySQL($data) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return null; // Retorna null se não tiver 3 partes
}

$encrypted_nome_b64 = $_POST['nome'] ?? '';
$encrypted_nascimento_b64 = $_POST['nascimento'] ?? '';
$encrypted_idresponsavel_b64 = $_POST['idresponsavel'] ?? '';
$encrypted_cpf_b64 = $_POST['cpf'] ?? '';
$encrypted_idprevenda_b64 = $_POST['idprevenda'] ?? '';
$encrypted_idvinculado_b64 = $_POST['idvinculado'] ?? '';
$encrypted_identrada_b64 = $_POST['identrada'] ?? '';


$encrypted_nome_raw = base64_decode($encrypted_nome_b64, true);
$encrypted_nascimento_raw = base64_decode($encrypted_nascimento_b64, true);
// $encrypted_vinculo_raw = base64_decode($encrypted_vinculo_b64, true);
// $encrypted_pacote_raw = base64_decode($encrypted_pacote_b64, true);
$encrypted_idresponsavel_raw = base64_decode($encrypted_idresponsavel_b64, true);
$encrypted_idprevenda_raw = base64_decode($encrypted_idprevenda_b64, true);
$encrypted_idvinculado_raw = base64_decode($encrypted_idvinculado_b64, true);
$encrypted_identrada_raw = base64_decode($encrypted_identrada_b64, true);



/*
if ($encrypted_nome_raw === false) {
    die("Erro ao decodificar base64.");
}

if (strlen($encrypted_nome_raw) !== 256) {
    die("Tamanho incorreto: esperado 256 bytes, recebido " . strlen($encrypted_nome_raw));
}
*/
try {
    $nome = $privateKey->decrypt($encrypted_nome_raw);
    $nascimento = $privateKey->decrypt($encrypted_nascimento_raw);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel_raw);
    $idprevenda = $privateKey->decrypt($encrypted_idprevenda_raw);
    $idvinculado = $privateKey->decrypt($encrypted_idvinculado_raw);
    $identrada = $privateKey->decrypt($encrypted_identrada_raw);
} catch (Exception $e) {
    die("Erro ao descriptografar: " . $e->getMessage());
}

$nascimento = dataParaMySQL($nascimento);


/*


use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

 $encrypted_nome_b64 = $_POST['nome'] ?? '';
$encrypted_nome_raw = base64_decode($encrypted_nome_b64, true);

if ($encrypted_nome_raw === false) {
    die("Erro ao decodificar base64.");
}

echo 'Tamanho do conteúdo decodificado: ' . strlen($encrypted_nome_raw) . ' bytes';
*/

// Deve retornar: 256 bytes


   /* 


$encrypted_nome      = base64_decode($_POST['nome'] ?? '');
$encrypted_nascimento = base64_decode($_POST['nascimento'] ?? '');
$encrypted_vinculo   = base64_decode($_POST['vinculo'] ?? '');
$encrypted_pacote    = base64_decode($_POST['pacote'] ?? '');
$encrypted_idresponsavel = base64_decode($_POST['idresponsavel'] ?? '');
$encrypted_idprevenda    = base64_decode($_POST['idprevenda'] ?? '');
$encrypted_idvinculado   = base64_decode($_POST['idvinculado'] ?? '');
$encrypted_identrada     = base64_decode($_POST['identrada'] ?? '');


try {
    //$idprevenda    = $privateKey->decrypt($encrypted_i);
    $nome          = $privateKey->decrypt($encrypted_nome);
    $nascimento    = $privateKey->decrypt($encrypted_nascimento);
    $vinculo       = $privateKey->decrypt($encrypted_vinculo);
    $pacote        = $privateKey->decrypt($encrypted_pacote);
    $idresponsavel = $privateKey->decrypt($encrypted_idresponsavel);
    $idprevenda    = $privateKey->decrypt($encrypted_idprevenda);
    $idvinculado   = $privateKey->decrypt($encrypted_idvinculado);
    $identrada     = $privateKey->decrypt($encrypted_identrada);

} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}


*/


$lembrar       = (isset($_POST['melembrar'])?1:0);
$vinculo = $_POST['vinculo'] ?? '';
$pacote  = $_POST['pacote'] ?? '';


if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');





$idresponsavelSessao = $_SESSION['dadosResponsavel'][0]['id_responsavel'];
$idresponsavel = $idresponsavelSessao;


//verificar se o idvinculado pertence ao idresponsavel
$sql = "select * from tbvinculados where id_vinculado=:idvinculado and id_responsavel=:idresponsavel";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idvinculado', $idvinculado, PDO::PARAM_INT);
$pre->bindParam(':idresponsavel', $idresponsavel, PDO::PARAM_INT);
$pre->execute();
if ($pre->rowCount() > 0) {
    
    // $sql1 = "update tbvinculados set  nome=:nome, nascimento=:nascimento, tipo=:tipo, lembrar=:lembrar where id_vinculado=:idvinculado";
    $sql1 = "update tbvinculados set  nome=:nome, nascimento=:nascimento, tipo=:tipo, lembrar=:lembrar where id_vinculado=:idvinculado and id_responsavel=:idresponsavelsessao";

    $pre1 = $connPDO->prepare($sql1);
    $pre1->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre1->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
    $pre1->bindParam(':tipo', $vinculo, PDO::PARAM_STR);
    $pre1->bindParam(':lembrar', $lembrar, PDO::PARAM_INT);
    $pre1->bindParam(':idvinculado', $idvinculado, PDO::PARAM_INT);
    $pre1->bindParam(':idresponsavelsessao', $idresponsavelSessao, PDO::PARAM_INT);


    $pre1->execute();

    $sql2 = "update tbentrada set perfil_acesso=:pacote, autoriza=0 where id_entrada=:identrada";

    $pre2 = $connPDO->prepare($sql2);
    $pre2->bindParam(':pacote', $pacote, PDO::PARAM_INT);
    $pre2->bindParam(':identrada', $identrada, PDO::PARAM_INT);

    $pre2->execute();
}


?>

