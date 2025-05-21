<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\PublicKeyLoader;



if ($_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}

session_start(); 

// die(file_get_contents('php://input'));

include('../inc/conexao.php');
$pdo = $connPDO;

// Defina as configurações do banco de dados
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'bdbilhetagem';
$username = 'root';
$password = '';


// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

*/
// Recebe os dados enviados em formato JSON


/*

// Recebe o JSON com o payload criptografado
$received = json_decode(file_get_contents('php://input'), true);

if (!isset($received['payload'])) {
    http_response_code(400);
    exit("Payload ausente");
}

$encrypted = base64_decode($received['payload']);

// Carrega sua chave privada (RSA-OAEP com SHA-256)
$privateKey = PublicKeyLoader::load(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
                              ->withHash('sha256')
                              ->withPadding(\phpseclib3\Crypt\RSA::ENCRYPTION_OAEP);

try {
    // Descriptografa e converte o JSON para array
    $decrypted = $privateKey->decrypt($encrypted);
    $data = json_decode($decrypted, true); // ✅ $data já é o array esperado

    if (!is_array($data)) {
        throw new Exception("JSON inválido após descriptografia.");
    }

    // Agora o restante do seu código pode usar $data normalmente

} catch (Exception $e) {
    http_response_code(500);
    exit("Erro ao descriptografar: " . $e->getMessage());
}

*/




// Caminho para a chave privada
$privateKeyPEM = file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem');


// Ler JSON do input raw
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['payload']['key'], $input['payload']['iv'], $input['payload']['data'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Payload inválido ou incompleto']);
    exit;
}

$encryptedKey = base64_decode($input['payload']['key']);
$iv           = base64_decode($input['payload']['iv']);
$cipherText   = base64_decode($input['payload']['data']);

// Obter recurso da chave privada
$privateKey = openssl_pkey_get_private($privateKeyPEM);
if (!$privateKey) {
    http_response_code(500);
    echo json_encode(['error' => 'Chave privada RSA inválida']);
    exit;
}

// Descriptografar chave AES com RSA
$ok = openssl_private_decrypt($encryptedKey, $aesKey, $privateKey, OPENSSL_PKCS1_OAEP_PADDING);
if (!$ok) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao descriptografar a chave AES']);
    exit;
}

// Extrair tag (últimos 16 bytes) do ciphertext e descriptografar com AES-GCM
$tagLength = 16;
$tag = substr($cipherText, -$tagLength);
$encryptedData = substr($cipherText, 0, -$tagLength);

$json = openssl_decrypt(
    $encryptedData,
    'aes-256-gcm',
    $aesKey,
    OPENSSL_RAW_DATA,
    $iv,
    $tag
);

if ($json === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao descriptografar os dados com AES-GCM']);
    exit;
}

// Decodificar JSON para array
$decoded = json_decode($json, true);

if (!is_array($decoded)) {
    http_response_code(500);
    echo json_encode(['error' => 'JSON descriptografado inválido']);
    exit;
}

// Garantir que $data terá as chaves que você precisa, com valores padrão
$data = [
    'id_entrada'       => $decoded['id_entrada'] ?? null,
    'userAgent'        => $decoded['userAgent'] ?? '',
    'screenResolution' => $decoded['screenResolution'] ?? '',
    'deviceType'       => $decoded['deviceType'] ?? '',
    'browserLanguage'  => $decoded['browserLanguage'] ?? '',
    'operatingSystem'  => $decoded['operatingSystem'] ?? '',
    'timeZone'         => $decoded['timeZone'] ?? '',
    'connectionType'   => $decoded['connectionType'] ?? '',
];

// Agora você pode usar $data diretamente no seu código normalmente

// (Opcional) Resposta para confirmar sucesso
// echo json_encode(['status' => 'ok', 'data' => $data]);





// $data = json_decode(file_get_contents('php://input'), true);

// Verifica se "id_entrada" foi passado e extrai as informações enviadas
$idEntrada = $data['id_entrada'] ?? null;
$userAgent = $data['userAgent'] ?? '';
$screenResolution = $data['screenResolution'] ?? '';
$deviceType = $data['deviceType'] ?? '';
$browserLanguage = $data['browserLanguage'] ?? '';
$operatingSystem = $data['operatingSystem'] ?? '';
$timeZone = $data['timeZone'] ?? '';
$connectionType = $data['connectionType'] ?? '';

// Informações adicionais coletadas pelo PHP
$ipAddress = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
             (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
$serverLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$serverDateTime = date('Y-m-d H:i:s');


//coleta dados do termo de uso ativo
$sql_busca_termo = "SELECT tbtermo.* FROM tbtermo
inner join tbprevenda on tbprevenda.id_evento=tbtermo.idevento
inner join tbentrada on tbentrada.id_prevenda=tbprevenda.id_prevenda
WHERE tbtermo.ativo=1 and tbentrada.id_entrada=$idEntrada";
// die($sql_busca_termo);
$pre_busca_termo = $connPDO->prepare($sql_busca_termo);
$pre_busca_termo->execute();
$row_busca_termo = $pre_busca_termo->fetchAll();

$idTermoAtivo = $row_busca_termo[0]['idtermo'];
// die(var_dump($row_busca_termo));


// Verifica se o id_entrada já existe no banco de dados
$stmt = $pdo->prepare("SELECT COUNT(*) FROM device_info WHERE id_entrada = :idEntrada");
$stmt->execute([':idEntrada' => $idEntrada]);
$exists = $stmt->fetchColumn();

try {
    if ($exists) {
        // Atualiza os dados se id_entrada já existir
        $stmt = $pdo->prepare("UPDATE device_info SET 
            ip_address = :ipAddress, 
            user_agent = :userAgent, 
            screen_resolution = :screenResolution, 
            device_type = :deviceType, 
            browser_language = :browserLanguage, 
            server_language = :serverLanguage, 
            operating_system = :operatingSystem, 
            time_zone = :timeZone, 
            connection_type = :connectionType, 
            server_date_time = :serverDateTime, 
            created_at = NOW(),
            termoativo = :idTermoAtivo 
            WHERE id_entrada = :idEntrada");

        $stmt->execute([
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
            ':idEntrada' => $idEntrada,
            ':idTermoAtivo' => $idTermoAtivo
        ]);
    } else {
        // Insere os dados se id_entrada não existir
        $stmt = $pdo->prepare("INSERT INTO device_info (
            id_entrada, ip_address, user_agent, screen_resolution, device_type, 
            browser_language, server_language, operating_system, 
            time_zone, connection_type, server_date_time, created_at, termoativo
        ) VALUES (
            :idEntrada, :ipAddress, :userAgent, :screenResolution, :deviceType, 
            :browserLanguage, :serverLanguage, :operatingSystem, 
            :timeZone, :connectionType, :serverDateTime, NOW(), :idTermoAtivo
        )");

        $stmt->execute([
            ':idEntrada' => $idEntrada,
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
            ':idTermoAtivo' => $idTermoAtivo
        ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Device info processed successfully']);
} catch (PDOException $e) {
    error_log($e->getMessage()); // Log the error message for internal review
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing your request.']);
}
?>
