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



/*
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
*/

if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
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
        <input type="hidden" name="participante" value="<?= htmlspecialchars($identrada) ?>">
        <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" name="btaddparticipante">Salvar e autorizar</button>
    </div>
</form>


<script>
    
    $(document).ready(function(){
        $('#formAceitaTermo').submit(function(e){
            e.preventDefault();
            let Form = $(this).serialize();
            const idPrevenda = $('#formAceitaTermo').data('id-prevenda');
            $.post('./blocos/aceita-termo.php', Form, function(data){
                // console.log(data);
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i: idPrevenda}, function(){
                    $('#modalTermoParticipante').modal('hide');
                });
                
            })
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


