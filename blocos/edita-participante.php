<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');


$encrypted_participante      = base64_decode($_POST['p'] ?? '');
$encrypted_prevenda         = base64_decode($_POST['e'] ?? '');

try {
    // $idprevenda    = $privateKey->decrypt($encrypted_i);
    $participante  = $privateKey->decrypt($encrypted_participante);
    $prevenda      = $privateKey->decrypt($encrypted_prevenda);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}



if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$sql = "select tbvinculados.id_vinculado, tbentrada.id_entrada, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbentrada.id_pacote, tbvinculados.tipo as id_tipovinculo,  tbvinculados.lembrar, tbentrada.perfil_acesso
from tbvinculados
inner join tbentrada on tbvinculados.id_vinculado=tbentrada.id_vinculado
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:prevenda and tbentrada.id_vinculado=:participante";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':participante', $participante, PDO::PARAM_INT);
$pre->bindParam(':prevenda', $prevenda, PDO::PARAM_INT);
$pre->execute();

$row = $pre->fetchAll();

?>
<form action="" method="post" id="formEditaParticipante">
    <div class="modal-header">
        <h4 class="title" id="modalEditaParticipanteLabel">Editar dados participante</h4>
    </div>
    <div class="modal-body"> 
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome</label>                               
                    <input name="nome" type="text" class="form-control" placeholder="Nome" value="<?= $row[0]['nome'] ?>" required />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Nascimento</label>                            
                    <input name="nascimento" id="nasc" type="text" placeholder="dd/mm/aaaa" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required value="<?= convertDateToDMY($row[0]['nascimento']) ?>" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Tipo de vínculo</label>                            
                    <select name="vinculo" class="form-control show-tick p-0" name="vinculo">
                        <option value="">Escolha</option>
                        <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                            <option <?= ($v['id_vinculo']==$row[0]['id_tipovinculo']?'selected':'') ?>  value="<?= $v['id_vinculo'] ?>" ><?= $v['descricao'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Perfil</label>
                    <select class="form-control p-0" name="pacote">
                        <option value="">Escolha</option>
                        <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                            <option <?= ($v['idperfil']==$row[0]['perfil_acesso']?'selected':'') ?> value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    
                    <div class="checkbox">
                        <input id="melembrar" name="melembrar" type="checkbox" value="1" <?= ($row[0]['lembrar']?'checked':'') ?>>
                        <label for="melembrar">Lembrar este participante?</label>
                    </div>
                </div>
            </div> 
        </div>   
    </div>
    <div class="modal-footer">
        <input type="hidden" name="idresponsavel" value="<?= $_SESSION['dadosResponsavel'][0]['id_responsavel']; ?>">
        <input type="hidden" name="cpf" value="<?= $_SESSION['dadosResponsavel'][0]['cpf']; ?>">
        <input type="hidden" name="idprevenda" value="<?= $_SESSION['idPrevenda'] ?>">
        <input type="hidden" name="idvinculado" value="<?= $participante ?>">
        <input type="hidden" name="identrada" value="<?= $row[0]['id_entrada'] ?>">
        
        <button type="button" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary btn-round waves-effect addparticipante" name="btaddparticipante">Salvar e adicionar</button>
        
    </div>
</form>

<script>
    $(document).ready(function(){
        $('select').selectpicker();
    });
</script>

<script>
$(document).ready(function() {
    // Função para calcular a idade com base na data de nascimento
    function calculateAge(date) {
        const today = new Date();
        const birthDate = new Date(date);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    // Monitorar alterações no campo de data de nascimento
    $('#nasc').on('change', function() {
        const birthDate = $(this).val();
        if (birthDate) {
            const age = calculateAge(birthDate);
            $('#idade').text(age);
            $('#infoNascimento').show();
        } else {
            $('#infoNascimento').hide();
        }
    });

    // Disparar o evento change para lidar com casos onde o campo já está preenchido no carregamento da página
    $('#nasc').trigger('change');
 
    $('#nasc').mask('00/00/0000');

    // Validação personalizada para verificar se a data é válida
    $('#nasc').on('input blur', function() {
        var date = $(this).val();
        if (!isValidDate(date) && date.length === 10) {
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid');
        }
    });

    function isValidDate(dateString) {
        var parts = dateString.split("/");
        if (parts.length !== 3) return false;

        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10) - 1; // meses são baseados em zero
        var year = parseInt(parts[2], 10);

        var date = new Date(year, month, day);
        return date.getFullYear() === year && date.getMonth() === month && date.getDate() === day;
    }
 
  async function criptografarCamposExtras(campos) {
    try {
        // Converte o objeto campos para JSON (string)
        const jsonString = JSON.stringify(campos);

        // Gera chave AES-256 (32 bytes) aleatória
        const aesKey = crypto.getRandomValues(new Uint8Array(32));

        // Gera vetor de inicialização (IV) para AES-GCM (12 bytes)
        const iv = crypto.getRandomValues(new Uint8Array(12));

        // Codifica string JSON em Uint8Array
        const encoder = new TextEncoder();
        const encodedData = encoder.encode(jsonString);

        // Importa chave AES para uso com SubtleCrypto
        const aesCryptoKey = await crypto.subtle.importKey(
            "raw", 
            aesKey, 
            { name: "AES-GCM" }, 
            false, 
            ["encrypt"]
        );

        // Criptografa os dados JSON usando AES-GCM
        const encryptedData = await crypto.subtle.encrypt(
            { name: "AES-GCM", iv: iv }, 
            aesCryptoKey, 
            encodedData
        );

        // Importa a chave RSA pública no formato SPKI
        const rsaKey = await crypto.subtle.importKey(
            "spki",
            pemToArrayBuffer(publicKeyPEM),  // publicKeyPEM deve estar disponível
            { name: "RSA-OAEP", hash: "SHA-256" },
            false,
            ["encrypt"]
        );

        // Criptografa a chave AES usando RSA-OAEP
        const encryptedAesKey = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            rsaKey,
            aesKey
        );

        // Converte ArrayBuffers para Base64 para transporte fácil
        const result = {
            key: arrayBufferToBase64(encryptedAesKey),
            iv: arrayBufferToBase64(iv),
            data: arrayBufferToBase64(encryptedData)
        };

        // Retorna JSON string contendo os dados criptografados
        return JSON.stringify(result);

    } catch (error) {
        console.error("Erro ao criptografar campos extras:", error);
        return null;
    }
}


function pemToArrayBuffer(pem) {
    const b64 = pem
        .replace(/-----BEGIN PUBLIC KEY-----/, '')
        .replace(/-----END PUBLIC KEY-----/, '')
        .replace(/\s/g, '');
    const binary = atob(b64);
    const buffer = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
        buffer[i] = binary.charCodeAt(i);
    }
    return buffer.buffer;
}

function arrayBufferToBase64(buffer) {
    const binary = Array.from(new Uint8Array(buffer), byte => String.fromCharCode(byte)).join('');
    return window.btoa(binary);
}


$('#formEditaParticipante').submit(async function(e) {
    e.preventDefault();

    const form = this;

    // Validação da data de nascimento (ajuste para seu campo)
    const dateInput = $('#nasc').val();
    if (!isValidDate(dateInput)) {
        $('#nasc').val('');
        alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
        $('#nasc').focus();
        return;
    }

    // Captura os valores de 'vinculo' e 'pacote' ANTES da criptografia
    const vinculoOriginal = $(form).find('[name="vinculo"]').val();
    const pacoteOriginal = $(form).find('[name="pacote"]').val();

    // Importa a chave pública
    const key = await crypto.subtle.importKey(
        "spki",
        pemToArrayBuffer(publicKeyPEM),
        { name: "RSA-OAEP", hash: "SHA-256" },
        false,
        ["encrypt"]
    );

    const encoder = new TextEncoder();
    const encryptedFields = {};

    // Criptografa inputs visíveis (exceto 'vinculo' e 'pacote')
    const visibleInputs = $(form).find('input[type!="hidden"][name]');
    for (let i = 0; i < visibleInputs.length; i++) {
        const input = visibleInputs[i];
        const name = input.name;
        const value = input.value;

        if (!name || !value || name === 'vinculo' || name === 'pacote') continue;

        const encrypted = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            key,
            encoder.encode(value)
        );
        const encoded = arrayBufferToBase64(encrypted);
        encryptedFields[name] = encoded;
    }

    // Criptografa inputs hidden (exceto 'vinculo' e 'pacote')
    const hiddenInputs = $(form).find('input[type="hidden"][name]');
    for (let i = 0; i < hiddenInputs.length; i++) {
        const input = hiddenInputs[i];
        const name = input.name;
        const value = input.value;

        if (!name || !value || name === 'vinculo' || name === 'pacote') continue;

        const encrypted = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            key,
            encoder.encode(value)
        );
        const encoded = arrayBufferToBase64(encrypted);
        encryptedFields[name] = encoded;
    }

    // Adiciona os valores de 'vinculo' e 'pacote' diretamente (sem criptografia)
    encryptedFields['vinculo'] = vinculoOriginal;
    encryptedFields['pacote'] = pacoteOriginal;

    // Envia os dados criptografados + 'vinculo' e 'pacote' puros
    $.ajax({
        type: 'POST',
        url: './blocos/participante-atualiza.php',
        data: encryptedFields,
        success: function(data) {
            console.log(data);

            // Atualiza bloco com idprevenda criptografado (opcional)
            const idprevenda = $(form).find('input[name="idprevenda"]').val();
            if (idprevenda) {
                encryptRSA(idprevenda, publicKeyPEM).then(encryptedId => {
                    $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: encryptedId }, function() {
                        $('#modalEditaParticipante').modal('toggle');
                    });
                });
            } else {
                $('#modalEditaParticipante').modal('toggle');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao enviar dados:', error);
            alert('Erro ao enviar os dados criptografados.');
        }
    });
});

});
</script>

