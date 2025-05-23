<!-- Large Size -->
<div class="modal fade" id="modalAddParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalAddParticipante">
                <div class="modal-header">
                    <h4 class="title" id="modalAddParticipanteLabel">Adicionar participantes</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fnome" class="form-label">Nome <span id="infoNascimento">(Idade: <span id="idade">2</span> anos)</span></label>                                
                                <input name="nome" id="fnome" type="text" class="form-control" placeholder="Nome" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fanscimento" class="form-label">Nascimento</label>                            
                                <input name="nascimento" id="fnascimento" type="text" placeholder="dd/mm/aaaa" class="form-control" placeholder="Nascimento" pattern="\d{2}/\d{2}/\d{4}" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fvinculo" class="form-label">Tipo de vínculo</label>                            
                                <select name="vinculo" class="form-control show-tick p-0" name="vinculo" id="fvinculo">
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_vinculo'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fpacote" class="form-label">Perfil</label>                            
                                <select class="form-control p-0" name="pacote" id="fpacote">
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                                        <option  value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="checkbox">
                                <input id="lembrarme" name="lembrarme" type="checkbox" value="1">
                                <label for="lembrarme">Lembrar este participante?</label>
                            </div>
                        </div> 
                    </div>   
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idresponsavel" value="<?= $dados_responsavel[0]['id_responsavel']; ?>">
                    <input type="hidden" name="cpf" value="<?= $dados_responsavel[0]['cpf']; ?>">
                    <input type="hidden" name="idprevenda" value="<?= $idPrevendaAtual ?>">
                    <button type="button" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-round waves-effect addparticipante" name="btaddparticipante" style="background-color: #27ae60!important">Salvar e adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditaParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="formParticipanteContent">
            
        </div>
    </div>
</div>

<div class="modal fade" id="modalTermoParticipante" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" id="formTermoParticipante">
           
        </div>
    </div>
</div>
<div id="idprevenda" data-id-prevenda="<?= $idPrevendaAtual ?>" style="display: none"></div>

<script>
if (typeof publicKeyPEM === 'undefined') {
        var publicKeyPEM = `-----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0BxUXjrrGvXDCIplSQ7l
    XfPN1PHujl9CTumnjnM58/2vCtkEaqNbVMXbqhFbqSIpbd1J2k6nn9QMyEvA2uLe
    kVgQhMBhxtxFNnuMYWJAeLddas1+Vhn5jygLhdk+PxZSXi/ZKrrCqq1QwA+PSeRq
    aL4StVkBNCaxXRElxWXjsPVm0JUgXAuAfzBwGeKwelSUjgoTAmTLcNOOxDL+LGYD
    x7IM5PjofaiJwLj3oQpkcfsxvDZ3SMpj/Jo+V+i8OBQwCyVOAfOEvUN+O1YZlBUT
    LcM7KvDLMtcQyGf//3QsjLsfqa/XEAvdAISjHO5TNAXy9MXPiEwd1cPyis7toz/d
    mQIDAQAB
    -----END PUBLIC KEY-----`;
}

if (typeof pemToArrayBuffer === 'undefined') {        
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
}

if (typeof arrayBufferToBase64 === 'undefined') {
    function arrayBufferToBase64(buffer) {
        const binary = Array.from(new Uint8Array(buffer), byte => String.fromCharCode(byte)).join('');
        return window.btoa(binary);
    }
}

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
    $('input[name=nascimento]').on('change', function() {
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
    $('input[name=nascimento]').trigger('change');
   
    $('input[name=nascimento]').mask('00/00/0000');

    // Validação personalizada para verificar se a data é válida
    $('input[name=nascimento]').on('input blur', function() {
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
/*

$('#formModalAddParticipante').on('submit', async function(event) {
    event.preventDefault();
    const $form = $(this);
    const $submitBtn = $form.find('button[type=submit]');
    $submitBtn.attr('disabled', true);

    const dateInput = $form.find('input[name=nascimento]').val();
    if (!isValidDate(dateInput)) {
        $form.find('input[name=nascimento]').val('');
        alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
        $form.find('input[name=nascimento]').focus();
        $submitBtn.attr('disabled', false);
        return;
    }

    // Extrai campos não criptografados
    const vinculo = $form.find('select[name="vinculo"]').val();
    const pacote  = $form.find('select[name="pacote"]').val();

    // Obtém o valor de idprevenda (do atributo data-id-prevenda)
    const idprevenda = $('#idprevenda').data('id-prevenda');

    try {
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

        // Criptografa os inputs visíveis (exceto os select especiais)
        const visibleInputs = $form.find('input[name], textarea[name], select[name]').not('[type=hidden]');
        for (let i = 0; i < visibleInputs.length; i++) {
            const input = visibleInputs[i];
            const name = input.name;
            const value = input.value;

            if (!name || !value) continue;
            if (name === 'vinculo' || name === 'pacote') continue;

            const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(value));
            encryptedFields[name + '_seguro'] = arrayBufferToBase64(encrypted);
        }

        // Criptografa os inputs hidden
        const hiddenInputs = $form.find('input[type="hidden"][name]');
        for (let i = 0; i < hiddenInputs.length; i++) {
            const input = hiddenInputs[i];
            const name = input.name;
            const value = input.value;
            if (!name || !value) continue;

            const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(value));
            encryptedFields[name] = arrayBufferToBase64(encrypted);
        }

        // Criptografa idprevenda
        if (idprevenda) {
            const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(String(idprevenda)));
            encryptedFields['idprevenda_seguro'] = arrayBufferToBase64(encrypted);
        }

        // Adiciona campos não criptografados
        encryptedFields['vinculo'] = vinculo;
        encryptedFields['pacote'] = pacote;

        // Envia os dados
        $.post('./blocos/add-participante.php', encryptedFields, function(data) {
            console.log('Dados enviados com sucesso:', data);
            // Após o envio bem-sucedido, recarrega lista vinculada
            $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: encryptedFields['idprevenda_seguro'] }, function() {
                location.reload();
            });
        }).fail(function() {
            alert('Erro ao enviar os dados criptografados.');
            $submitBtn.attr('disabled', false);
        });

    } catch (err) {
        console.error('Erro ao criptografar os dados:', err);
        alert('Erro de criptografia. Verifique a chave pública.');
        $submitBtn.attr('disabled', false);
    }
});

*/

/*
$('#formModalAddParticipante').on('submit', async function(event) {
    event.preventDefault();
    const $form = $(this);
    const $submitBtn = $form.find('button[type=submit]');
    $submitBtn.attr('disabled', true);

    const dateInput = $form.find('input[name=nascimento]').val();
    if (!isValidDate(dateInput)) {
        $form.find('input[name=nascimento]').val('');
        alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
        $form.find('input[name=nascimento]').focus();
        $submitBtn.attr('disabled', false);
        return;
    }

    const vinculo = $form.find('select[name="vinculo"]').val();
    const pacote = $form.find('select[name="pacote"]').val();
    const idprevenda = $('#idprevenda').data('id-prevenda');

    try {
        const encoder = new TextEncoder();

        // Objeto com todos os dados que você quer criptografar
        const dados = {
            nome: $form.find('input[name="nome"]').val(),
            nascimento: $form.find('input[name="nascimento"]').val(),
            idresponsavel: $form.find('input[name="idresponsavel"]').val(),
            idprevenda: String(idprevenda)
        };

        // Gera chave AES
        const aesKey = await crypto.subtle.generateKey({ name: "AES-GCM", length: 256 }, true, ["encrypt"]);

        // Gera IV aleatório
        const iv = crypto.getRandomValues(new Uint8Array(12));

        // Converte o JSON dos dados para string e criptografa com AES-GCM
        const dadosString = JSON.stringify(dados);
        const encryptedData = await crypto.subtle.encrypt(
            { name: "AES-GCM", iv: iv },
            aesKey,
            encoder.encode(dadosString)
        );

        // Converte a chave AES para raw para criptografar com RSA
        const rawAesKey = await crypto.subtle.exportKey("raw", aesKey);

        // Importa chave RSA pública
        const rsaKey = await crypto.subtle.importKey(
            "spki",
            pemToArrayBuffer(publicKeyPEM),
            { name: "RSA-OAEP", hash: "SHA-256" },
            false,
            ["encrypt"]
        );

        // Criptografa a chave AES com RSA
        const encryptedAesKey = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            rsaKey,
            rawAesKey
        );

        // Monta dados para enviar
        const payload = {
            dados_seguro: arrayBufferToBase64(encryptedData),
            chave_segura: arrayBufferToBase64(encryptedAesKey),
            iv: arrayBufferToBase64(iv),
            vinculo: vinculo,
            pacote: pacote
        };

        // Envia
        $.post('./blocos/add-participante.php', payload, function(data) {
            console.log('aqui: ', data);
            // $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: idprevenda }, function() {
            //     location.reload();
            // });
        }).fail(function() {
            alert('Erro ao enviar os dados criptografados.');
            $submitBtn.attr('disabled', false);
        });

    } catch (err) {
        console.error('Erro de criptografia:', err);
        alert('Erro de criptografia. Verifique a chave pública.');
        $submitBtn.attr('disabled', false);
    }
});

// Funções auxiliares
function arrayBufferToBase64(buffer) {
    return btoa(String.fromCharCode(...new Uint8Array(buffer)));
}

function pemToArrayBuffer(pem) {
    const b64 = pem.replace(/-----(BEGIN|END) PUBLIC KEY-----/g, '').replace(/\s+/g, '');
    const binary = atob(b64);
    const len = binary.length;
    const buffer = new ArrayBuffer(len);
    const view = new Uint8Array(buffer);
    for (let i = 0; i < len; i++) {
        view[i] = binary.charCodeAt(i);
    }
    return buffer;
}



*/




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



$('#formModalAddParticipante').on('submit', async function(event) {
    event.preventDefault();
    alert('ok');
});

/*

$('#formModalAddParticipante').on('submit', async function(event) {
// $('body').on('submit', '#formModalAddParticipante', async function(event) {
    e.preventDefault();
    alert('ok');

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
        url: './blocos/add-participante.php',
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


*/

    $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
        $('#modalAddParticipante form').trigger('reset');
     })
        
});
</script>
