<!-- Large Size -->
<div class="modal fade" id="modalAddParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalParticipante">
                <div class="modal-header">
                    <h4 class="title" id="modalAddParticipanteLabel">Adicionar participante</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome</label>                               
                                <input name="nome" type="text" class="form-control" placeholder="Nome" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Nascimento</label>                            
                                <input name="nascimento" type="text" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required placeholder="dd/mm/aaaa"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">Tipo de vínculo</label>                            
                                <select name="vinculo" class="form-control show-tick p-0" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_vinculo'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">Perfil</label>                            
                                <select class="form-control show-tick p-0" name="perfil" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                                        <option  value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Pacote</label>                            
                                <select class="form-control show-tick p-0" name="pacote" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_pacotes'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_pacote'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                   
                                </select>
                            </div>
                        </div>  -->
                        <div class="col-12">
                            <div class="form-group">
                                
                                <div class="checkbox">
                                    <input id="lembrarme" name="lembrarme" type="checkbox" value="1">
                                    <label for="lembrarme">Lembrar este participante?</label>
                                </div>
                            </div>
                        </div> 
                    </div>   
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idresponsavel" value="<?= $row[0]['id_responsavel'] ?>">
                    <input type="hidden" name="idprevenda" value="<?= $row[0]['id_prevenda'] ?>">
                    <button type="submit" class="btn btn-default btn-round waves-effect addparticipante">Salvar e adicionar</button>
                    <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="iditem" data-id-item="<?= htmlspecialchars($_GET['item']) ?>" style="display: none"></div>
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
        
    $(document).ready(function(){
        $('select').selectpicker();   


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
        
        $('#formModalParticipante').submit(function(event){
            event.preventDefault();
            $('#formModalParticipante button[type="submit"]').prop('disabled', true);
            let Form = $(this).serialize();
            let idItem = $('#iditem').data('id-item');

            var dateInput = $('input[name=nascimento]').val();
            if (!isValidDate(dateInput)) {
                
                $('input[name=nascimento]').val('');
                alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
                $('input[name=nascimento]').focus();
                $('#formModalParticipante button[type="submit"]').prop('disabled', false);
            } else {

                $.post( "./blocos/add-participante.php", Form, function(data){
                    // console.log(data);
                    $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i: idItem }, function(){
                        location.reload();
                    });
                    // $('#formModalParticipante button[type="submit"]').prop('disabled', false);
                    // $('#formModalParticipante').trigger('reset');
                    // $('#modalAddParticipante').modal('hide');
                }); 
            }
        });

        */

    $('#formModalParticipante').submit(async function(event){
        event.preventDefault();
        $('#formModalParticipante button[type="submit"]').prop('disabled', true);
        let idItem = $('#iditem').data('id-item');

        let dateInput = $('input[name=nascimento]').val();
        if (!isValidDate(dateInput)) {
            $('input[name=nascimento]').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('input[name=nascimento]').focus();
            $('#formModalParticipante button[type="submit"]').prop('disabled', false);
            return;
        }

        try {
            const encoder = new TextEncoder();
            const key = await crypto.subtle.importKey(
                "spki",
                pemToArrayBuffer(publicKeyPEM),
                { name: "RSA-OAEP", hash: "SHA-256" },
                false,
                ["encrypt"]
            );

            let formData = {};
            $('#formModalParticipante')
                .serializeArray()
                .forEach(field => {
                    formData[field.name] = field.value;
                });

            let encryptedData = {};
            for (let keyName in formData) {
                const encrypted = await crypto.subtle.encrypt(
                    { name: "RSA-OAEP" },
                    key,
                    encoder.encode(formData[keyName])
                );
                encryptedData[keyName] = arrayBufferToBase64(encrypted);
            }

            // Criptografa idItem
            const encryptedIdItem = await crypto.subtle.encrypt(
                { name: "RSA-OAEP" },
                key,
                encoder.encode(idItem.toString())
            );
            const idItemEncrypted = arrayBufferToBase64(encryptedIdItem);

            $.post("./blocos/add-participante.php", encryptedData, function(data){
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: idItemEncrypted }, function(){
                    location.reload();
                });
            }).fail(function() {
                alert("Erro ao enviar dados criptografados.");
            });

        } catch (err) {
            console.error("Erro na criptografia dos dados:", err);
            alert("Erro de segurança ao processar envio.");
        }
    });        


        $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
            $('#formModalParticipante').trigger('reset');
        })
 
    });    
</script>