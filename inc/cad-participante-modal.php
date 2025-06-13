<!-- Large Size -->
<div class="modal fade" id="modalAddParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalAddParticipante">
                <div class="modal-header">
                    <h4 class="title" id="modalAddParticipanteLabel">Adicionar participante</h4>
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
                                <label for="fvinculo" class="form-label">Vínculo com responsável principal</label>                            
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

    $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
        $('#modalAddParticipante form').trigger('reset');
     })
        
});
</script>


<script>

function isValidDate(dateStr) {
    if (typeof dateStr !== 'string') return false;

    // Garante formato dd/mm/aaaa
    const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
    const match = dateStr.match(regex);
    if (!match) return false;

    const dd = parseInt(match[1], 10);
    const mm = parseInt(match[2], 10);
    const yyyy = parseInt(match[3], 10);

    // Valida faixas básicas
    if (dd < 1 || mm < 1 || mm > 12 || yyyy < 1900 || yyyy > 2100) return false;

    // Cria e valida objeto Date
    const date = new Date(yyyy, mm - 1, dd);
    return (
        date.getFullYear() === yyyy &&
        date.getMonth() === mm - 1 &&
        date.getDate() === dd
    );
}


$('#formModalAddParticipante').on('submit', async function(event) {
    event.preventDefault(); // Corrigido

    const form = this;

    const submitBtn = $(form).find('button[type="submit"]');
    submitBtn.prop('disabled', true);

    // Validação da data de nascimento
    const dateInput = $('#fnascimento').val();
    if (!isValidDate(dateInput)) {
        $('#fnascimento').val('');
        alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
        $('#fnascimento').focus();
        submitBtn.prop('disabled', false);
        return;
    }

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

        // Coleta os campos visíveis (exceto vinculo e pacote)
        const visibleInputs = $(form).find('input[type!="hidden"][name], textarea[name], select[name]');
        for (let i = 0; i < visibleInputs.length; i++) {
            const input = visibleInputs[i];
            const name = input.name;
            const value = input.value;

            if (!name || !value) continue;
            if (name === 'vinculo' || name === 'pacote') continue;

            const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(value));
            encryptedFields[name] = arrayBufferToBase64(encrypted);
        }

        // Coleta campos hidden
        const hiddenInputs = $(form).find('input[type="hidden"][name]');
        for (let i = 0; i < hiddenInputs.length; i++) {
            const input = hiddenInputs[i];
            const name = input.name;
            const value = input.value;

            if (!name || !value) continue;

            const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(value));
            encryptedFields[name] = arrayBufferToBase64(encrypted);
        }

        // Captura dados não criptografados
        encryptedFields['vinculo'] = $(form).find('[name="vinculo"]').val();
        encryptedFields['pacote']  = $(form).find('[name="pacote"]').val();

        // Envia os dados para o backend
        $.ajax({
            type: 'POST',
            url: './blocos/add-participante.php',
            data: encryptedFields,
            success: function(data) {
                console.log('Resposta do servidor:', data);

                // Recarrega lista vinculada com ID criptografado, se houver
                const idprevenda = $(form).find('input[name="idprevenda"]').val();
                if (idprevenda) {
                    encryptRSA(idprevenda, publicKeyPEM).then(encryptedId => {
                        $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: encryptedId }, function() {
                            $('#modalAddParticipante').modal('hide');
                        });
                    }).catch(err => {
                        console.error("Erro ao criptografar idprevenda:", err);
                        alert("Erro ao processar ID do projeto.");
                        $('#modalAddParticipante').modal('hide');
                    });
                } else {
                    $('#modalAddParticipante').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao enviar dados:', error);
                alert('Erro ao enviar os dados criptografados.');
            },
            complete: function() {
                submitBtn.prop('disabled', false);
            }
        });
    } catch (err) {
        console.error('Erro de criptografia:', err);
        alert('Erro ao criptografar os dados. Verifique a chave pública.');
        submitBtn.prop('disabled', false);
    }
});


function pemToArrayBuffer(pem) {
    const b64 = pem.replace(/-----[^-]+-----/g, '').replace(/\s/g, '');
    const binary = atob(b64);
    const len = binary.length;
    const buffer = new ArrayBuffer(len);
    const view = new Uint8Array(buffer);
    for (let i = 0; i < len; i++) {
        view[i] = binary.charCodeAt(i);
    }
    return buffer;
}

function arrayBufferToBase64(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
}

async function encryptRSA(value, pem) {
    const key = await crypto.subtle.importKey(
        "spki",
        pemToArrayBuffer(pem),
        { name: "RSA-OAEP", hash: "SHA-256" },
        false,
        ["encrypt"]
    );
    const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, new TextEncoder().encode(value));
    return arrayBufferToBase64(encrypted);
}
</script>

