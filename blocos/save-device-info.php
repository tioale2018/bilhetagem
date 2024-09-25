<?php

/*
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
    */
session_start();

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
$data = json_decode(file_get_contents('php://input'), true);

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
            created_at = NOW() 
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
            ':idEntrada' => $idEntrada
        ]);
    } else {
        // Insere os dados se id_entrada não existir
        $stmt = $pdo->prepare("INSERT INTO device_info (
            id_entrada, ip_address, user_agent, screen_resolution, device_type, 
            browser_language, server_language, operating_system, 
            time_zone, connection_type, server_date_time, created_at
        ) VALUES (
            :idEntrada, :ipAddress, :userAgent, :screenResolution, :deviceType, 
            :browserLanguage, :serverLanguage, :operatingSystem, 
            :timeZone, :connectionType, :serverDateTime, NOW()
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
            ':serverDateTime' => $serverDateTime
        ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Device info processed successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
