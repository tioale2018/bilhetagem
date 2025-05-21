<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');

//$identrada = $_POST['i'];
// Decodifica a senha criptografada
$encrypted_id      = base64_decode($_POST['i'] ?? '');

try {
    $identrada        = $privateKey->decrypt($encrypted_id);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}



if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

session_start();

include('../inc/conexao.php');

function replaceVariables($text, $variables) {
    foreach ($variables as $key => $value) {
        // Cria o padrão da variável, por exemplo, {{var1}}
        $pattern = '{{' . $key . '}}';
        // Substitui todas as ocorrências do padrão no texto pelo valor correspondente
        $text = str_replace($pattern, $value, $text);
    }
    return $text;
}

function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

function formatDate($timestamp) {
    // Array com os nomes dos meses em português
    $months = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];
    
    // Extrai o dia, mês e ano do timestamp
    $day = date('d', $timestamp);
    $month = date('n', $timestamp);
    $year = date('Y', $timestamp);

    // Monta a data formatada
    $formattedDate = sprintf('%d de %s de %d', $day, $months[$month], $year);
    
    // Retorna a data formatada
    return $formattedDate;
}

$sql_busca_termo = "select tbtermo.*, tbevento.titulo, tbevento.local, tbevento.cidade from tbtermo inner join tbevento on tbevento.id_evento=tbtermo.idevento where tbtermo.ativo=1 and tbtermo.idevento=".$_SESSION['evento_atual'];
// die($sql_busca_termo);

$pre_busca_termo = $connPDO->prepare($sql_busca_termo);
$pre_busca_termo->execute();
$row_busca_termo = $pre_busca_termo->fetchAll();

// die(var_dump($row_busca_termo[0]));

// $identrada = $_POST['i'];
$sql_dados_participante = "SELECT tbentrada.id_prevenda, tbvinculados.nome as participantenome, tbvinculados.nascimento, tbresponsavel.nome as responsavelnome, tbresponsavel.cpf, tbresponsavel.telefone1, tbresponsavel.email FROM tbentrada inner join tbvinculados on tbvinculados.id_vinculado=tbentrada.id_vinculado inner join tbresponsavel on tbresponsavel.id_responsavel=tbvinculados.id_responsavel WHERE tbentrada.id_entrada=:identrada";

$pre_dados_participante = $connPDO->prepare($sql_dados_participante);
$pre_dados_participante->bindParam(':identrada', $identrada, PDO::PARAM_INT);
$pre_dados_participante->execute();
$row_dados_participante = $pre_dados_participante->fetchAll();

$dataAgora = time();

$row_participante = $row_dados_participante[0];


$variables = [
    'responsavelnome' => $row_dados_participante[0]['responsavelnome'],
    'responsavelcpf' => $row_dados_participante[0]['cpf'],
    'responsaveltel1' => $row_dados_participante[0]['telefone1'],
    'participantenome' => $row_dados_participante[0]['participantenome'],
    'participantenascimento' => date('d/m/Y', strtotime($row_dados_participante[0]['nascimento'])), 
    'participanteidade' => calculateAge($row_dados_participante[0]['nascimento']),
    'datahoje' => formatDate($dataAgora),
    'cidadetermo' => ($row_busca_termo[0]['cidadetermo']==''?'Rio de Janeiro':$row_busca_termo[0]['cidadetermo']),
    'empresa' => $row_busca_termo[0]['empresa'],
    'cnpj' => $row_busca_termo[0]['cnpj']
];

// include_once('../inc/variaveis-termo.php');
?>
<form action="" id="formAceitaTermo" method="post"  data-id-prevenda="<?= $row_dados_participante[0]['id_prevenda'] ?>">
    <div class="modal-header">
        <h4 class="title" id="modalTermoParticipanteLabel">Termo de responsabilidade</h4>
    </div>
    <div class="modal-body"> 
        <div class="row clearfix">
            <div class="col-md-12">
                <?= replaceVariables($row_busca_termo[0]['textotermo'], $variables); ?>
                <div class="">
                    <label for="assinatermo"><input data-identrada="<?= htmlspecialchars($identrada) ?>" id="assinatermo" name="assinatermo" type="checkbox" value="1" required> Confirmo que li o termo e estou de acordo com suas condições.</label>
                </div>
            </div>
        </div>   
    </div>
    <div class="modal-footer">
        <input type="hidden" name="participante" id="participante" value="<?= htmlspecialchars($identrada) ?>">
        <!-- <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" name="btaddparticipante" value="0">Salvar e autorizar</button> -->
        <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" id="btnSalvarTermo">Salvar e autorizar</button>

    </div>
</form>


<script>
//         const publicKeyPEM = `-----BEGIN PUBLIC KEY-----
// MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0BxUXjrrGvXDCIplSQ7l
// XfPN1PHujl9CTumnjnM58/2vCtkEaqNbVMXbqhFbqSIpbd1J2k6nn9QMyEvA2uLe
// kVgQhMBhxtxFNnuMYWJAeLddas1+Vhn5jygLhdk+PxZSXi/ZKrrCqq1QwA+PSeRq
// aL4StVkBNCaxXRElxWXjsPVm0JUgXAuAfzBwGeKwelSUjgoTAmTLcNOOxDL+LGYD
// x7IM5PjofaiJwLj3oQpkcfsxvDZ3SMpj/Jo+V+i8OBQwCyVOAfOEvUN+O1YZlBUT
// LcM7KvDLMtcQyGf//3QsjLsfqa/XEAvdAISjHO5TNAXy9MXPiEwd1cPyis7toz/d
// mQIDAQAB
// -----END PUBLIC KEY-----`;
    
    $(document).ready(function(){
        


$('#formAceitaTermo').submit(async function(e) {
    e.preventDefault();

    const form = this;
    const idPrevenda = $(form).data('id-prevenda');

    if (typeof encryptFormFields !== "function") {
        alert("Função de criptografia não encontrada. Verifique se safe.js foi carregado.");
        return;
    }

    try {
        // Criptografa campos "permitidos" por safe.js
        const encryptedData = await encryptFormFields(form, publicKeyPEM);

        // Configura chave pública
        const encoder = new TextEncoder();
        const pemContents = publicKeyPEM.replace(/-----.*?-----/g, "").replace(/\s/g, "");
        const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));
        const key = await crypto.subtle.importKey(
            "spki",
            binaryDer.buffer,
            { name: "RSA-OAEP", hash: "SHA-256" },
            false,
            ["encrypt"]
        );

        // Criptografa manualmente os inputs type=hidden
        const hiddenInputs = $(form).find('input[type="hidden"][name]');
        for (let i = 0; i < hiddenInputs.length; i++) {
            const input = hiddenInputs[i];
            const name = input.name;
            const value = input.value;

            if (!name || !value) continue;

            const encrypted = await crypto.subtle.encrypt(
                { name: "RSA-OAEP" },
                key,
                encoder.encode(value)
            );

            const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
            encryptedData[name] = encoded;
        }

        // Criptografa idPrevenda também
        const encryptedId = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            key,
            encoder.encode(idPrevenda.toString())
        );
        const encodedId = btoa(String.fromCharCode(...new Uint8Array(encryptedId)));
        // encryptedData['id_prevenda_seguro'] = encodedId;
        encryptedData['i'] = encodedId;

        // Remove os name dos inputs para evitar envio normal
        $(form).find('[name]').removeAttr('name');

        // Envia os dados criptografados
        alert(idPrevenda);
        $.post('./blocos/aceita-termo.php', encryptedData, function() {
            $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: idPrevenda }, function() {
                $('#modalTermoParticipante').modal('hide');
            });
        });

    } catch (error) {
        console.error("Erro ao criptografar o formulário:", error);
    }
});


    function getDeviceInfo(identrada) {
        // Coleta as informações do dispositivo via JavaScript
        const deviceInfo = {
            id_entrada: identrada,                                 // Adiciona o valor de id_entrada
            userAgent: navigator.userAgent,                        // User-Agent string
            screenResolution: `${window.screen.width}x${window.screen.height}`, // Screen resolution
            deviceType: /Mobile|Android|iP(hone|od|ad)/.test(navigator.userAgent) ? 'Mobile' : 'Desktop', // Device type
            browserLanguage: navigator.language || navigator.userLanguage, // Browser language
            operatingSystem: navigator.platform,                   // Operating system
            timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone, // Timezone
            connectionType: navigator.connection ? navigator.connection.effectiveType : 'unknown' // Connection type
        };

        return deviceInfo;
    }

function sendDeviceInfo(identrada) {
    deviceInfo = getDeviceInfo(identrada);

    // Envio dos dados usando jQuery AJAX
    $.ajax({
        url: './blocos/save-device-info.php',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(deviceInfo),
        success: function(response) {
            console.log('Device info saved:', response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error saving device info:', textStatus, errorThrown);
        }
    });
}

// Chame a função quando o checkbox for clicado
$('#assinatermo').on('change', function() {
    if ($(this).is(':checked')) {
        // Obtém o valor de identrada do atributo data-identrada
        const identrada = $(this).data('identrada');
        sendDeviceInfo(identrada); // Passa identrada para a função
    }
});


    })
</script>


