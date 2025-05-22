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

<script>
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
     $('#formModalAddParticipante').on('submit', function(event) {
        event.preventDefault();
        $('#formModalAddParticipante button[type=submit]').attr('disabled', true);
        
        let Form = $(this).serialize();
        let i = 1;

        var dateInput = $('input[name=nascimento]').val();
        if (!isValidDate(dateInput)) {
            
            $('input[name=nascimento]').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('input[name=nascimento]').focus();
        } else {

                $.post( "./blocos/add-participante.php", Form, function(data){
                    $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $idPrevendaAtual ?> }, function(){
                        location.reload();
                    });
                }); 
        }
    });
*/

$('#formModalAddParticipante').on('submit', async function(event) {
    event.preventDefault();
    const $form = $(this);
    $form.find('button[type=submit]').attr('disabled', true);

    const dateInput = $form.find('input[name=nascimento]').val();
    if (!isValidDate(dateInput)) {
        $form.find('input[name=nascimento]').val('');
        alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
        $form.find('input[name=nascimento]').focus();
        return;
    }

    // Extrair os campos não criptografados
    const vinculo = $form.find('select[name="vinculo"]').val();
    const pacote  = $form.find('select[name="pacote"]').val();

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

    // Campos visíveis (exceto "vinculo" e "pacote")
    const visibleInputs = $form.find('input[name], textarea[name], select[name]').not('[type=hidden]');
    for (let i = 0; i < visibleInputs.length; i++) {
        const input = visibleInputs[i];
        const name = input.name;
        const value = input.value;

        if (!name || !value) continue;

        if (name === 'vinculo' || name === 'pacote') continue; // pula os selects

        const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(value));
        encryptedFields[name + '_seguro'] = arrayBufferToBase64(encrypted);
    }

    // Campos hidden
    const hiddenInputs = $form.find('input[type="hidden"][name]');
    for (let i = 0; i < hiddenInputs.length; i++) {
        const input = hiddenInputs[i];
        const name = input.name;
        const value = input.value;
        if (!name || !value) continue;

        const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(value));
        encryptedFields[name] = arrayBufferToBase64(encrypted);
    }

    // Adiciona campos não criptografados
    encryptedFields['vinculo'] = vinculo;
    encryptedFields['pacote'] = pacote;

    // Envia via POST
    $.post('./blocos/add-participante.php', encryptedFields, function(data) {
        $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: <?= $idPrevendaAtual ?> }, function() {
            location.reload();
        });
    }).fail(function() {
        alert('Erro ao enviar os dados criptografados.');
        $form.find('button[type=submit]').attr('disabled', false);
    });
});


    $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
        $('#modalAddParticipante form').trigger('reset');
     })
        
});
</script>
