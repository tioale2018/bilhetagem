<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$participante = intval($_POST['i']);
$prevenda     = intval($_POST['j']);

// $sql = "update tbentrada set previnculo_status=2 where id_entrada=:entrada";
//$sql = "select * from tbvinculados where id_vinculado=:vinculado";
$sql = "select tbvinculados.id_vinculado, tbentrada.id_entrada, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbentrada.id_pacote, tbvinculados.tipo as id_tipovinculo,  tbvinculados.lembrar, tbentrada.perfil_acesso
from tbvinculados
inner join tbentrada on tbvinculados.id_vinculado=tbentrada.id_vinculado
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:prevenda and tbentrada.id_vinculado=:participante";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':participante', $participante, PDO::PARAM_INT);
$pre->bindParam(':prevenda', $prevenda, PDO::PARAM_INT);
$pre->execute();

$row = $pre->fetchAll();

// die(var_dump($_SESSION['lista_vinculos']));

?>
<form action="" method="post" id="formEditaParticipante">
    <div class="modal-header">
        <h4 class="title" id="modalEditaParticipanteLabel">Editar dados participante</h4>
    </div>
    <div class="modal-body"> 
        <div class="row clearfix">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome</label>                               
                    <input name="nome" type="text" class="form-control" placeholder="Nome" value="<?= $row[0]['nome'] ?>" required />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Nascimento</label>                            
                    <input name="nascimento" id="nascEdita" type="text" class="form-control" value="<?= convertDateToDMY($row[0]['nascimento']) ?>" pattern="\d{2}/\d{2}/\d{4}" required placeholder="dd/mm/aaaa" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label">Tipo de vínculo</label>                            
                    <select name="vinculo" class="form-control show-tick p-0" name="vinculo" required>
                        <option value="">Escolha</option>
                        <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                            <option <?= ($v['id_vinculo']==$row[0]['id_tipovinculo']?'selected':'') ?>  value="<?= $v['id_vinculo'] ?>" ><?= $v['descricao'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="form-label">Perfil</label>
                    <select class="form-control p-0" name="perfil" required>
                        <option value="">Escolha</option>
                        <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                            <option <?= ($v['idperfil']==$row[0]['perfil_acesso']?'selected':'') ?> value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?> </option>
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
                            <option <?= ($v['id_pacote']==$row[0]['id_pacote']?'selected':'') ?>  value="<?= $v['id_pacote'] ?>"><?= $v['descricao'] ?></option>
                        <?php } ?>
                        
                    </select>
                </div>
            </div>  -->
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
        
        <input type="hidden" name="idvinculado" value="<?= $participante ?>">
        <input type="hidden" name="identrada" value="<?= $row[0]['id_entrada'] ?>">
        
        <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" name="btaddparticipante">Salvar e adicionar</button>
        <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
    </div>
</form>

<div id="idprevenda" style="display: none;" data-id-prevenda="<?= $prevenda ?>"></div>


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


// Define a função de conversão PEM → ArrayBuffer
function pemToArrayBuffer(pem) {
    const b64 = pem.replace(/-----BEGIN PUBLIC KEY-----/, '')
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
    const binary = String.fromCharCode(...new Uint8Array(buffer));
    return btoa(binary);
}


$(document).ready(function() {

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
    $('#nascEdita').on('change', function() {
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
    $('#nascEdita').trigger('change');

    $('#nascEdita').mask('00/00/0000');

    // Validação personalizada para verificar se a data é válida
    $('#nascEdita').on('input blur', function() {
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
      
    $('select').selectpicker();

    /*
    $('#formEditaParticipante').submit(function(e){
        e.preventDefault();
        $('#formEditaParticipante button[type=submit]').prop('disabled', true);
        let Form = $(this);
        let prevenda = $('#idprevenda').data('id-prevenda');

        var dateInput = $('#nascEdita').val();
        if (!isValidDate(dateInput)) {
            
            $('#nascEdita').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('#nascEdita').focus();
            $('#formEditaParticipante button[type=submit]').prop('disabled', false);
        } else {

            $.post('./blocos/participante-atualiza.php', Form.serialize(), function(data){
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:prevenda }, function(){
                    $('#formEditaParticipante button[type=submit]').prop('disabled', false);
                    $('#modalEditaParticipante').modal('toggle');
                });

            });
        }
    });
    */

/*
    $('#formEditaParticipante').submit(async function(e) {
        e.preventDefault();
        $('#formEditaParticipante button[type=submit]').prop('disabled', true);

        let Form = $(this);
        let prevenda = $('#idprevenda').data('id-prevenda');

        let dateInput = $('#nascEdita').val();
        if (!isValidDate(dateInput)) {
            $('#nascEdita').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('#nascEdita').focus();
            $('#formEditaParticipante button[type=submit]').prop('disabled', false);
            return;
        }

        try {

            function pemToArrayBuffer(pem) {
                const b64 = pem.replace(/-----(BEGIN|END) PUBLIC KEY-----/g, '').replace(/\s/g, '');
                const bin = atob(b64);
                return Uint8Array.from([...bin].map(c => c.charCodeAt(0))).buffer;
            }

            function arrayBufferToBase64(buffer) {
                return btoa(String.fromCharCode(...new Uint8Array(buffer)));
            }

            const encoder = new TextEncoder();
            const key = await crypto.subtle.importKey(
                "spki",
                pemToArrayBuffer(publicKeyPEM),
                { name: "RSA-OAEP", hash: "SHA-256" },
                false,
                ["encrypt"]
            );

            const encryptedPrevenda = await crypto.subtle.encrypt(
                { name: "RSA-OAEP" },
                key,
                encoder.encode(prevenda.toString())
            );
            const prevendaEncrypted = arrayBufferToBase64(encryptedPrevenda);

            // Envia os dados do formulário (sem criptografar os outros campos) + prevenda criptografado
            $.post('./blocos/participante-atualiza.php', Form.serialize(), function(data){
                $.post('./blocos/lista-vinculados.php', { i: prevendaEncrypted }, function(html) {
                    $('.bloco-vinculados').html(html);
                    $('#formEditaParticipante button[type=submit]').prop('disabled', false);
                    $('#modalEditaParticipante').modal('toggle');
                });
            });

        } catch (err) {
            console.error("Erro na criptografia de prevenda:", err);
            alert("Erro ao processar dados com segurança.");
            $('#formEditaParticipante button[type=submit]').prop('disabled', false);
        }
    });
    */

    
    $('#formEditaParticipante').submit(async function(e) {
        e.preventDefault();
        $('#formEditaParticipante button[type=submit]').prop('disabled', true);

        let Form = $(this);
        let prevenda = $('#idprevenda').data('id-prevenda');

        let dateInput = $('#nascEdita').val();
        if (!isValidDate(dateInput)) {
            $('#nascEdita').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('#nascEdita').focus();
            $('#formEditaParticipante button[type=submit]').prop('disabled', false);
            return;
        }

        try {

            function pemToArrayBuffer(pem) {
                const b64 = pem.replace(/-----(BEGIN|END) PUBLIC KEY-----/g, '').replace(/\s/g, '');
                const bin = atob(b64);
                return Uint8Array.from([...bin].map(c => c.charCodeAt(0))).buffer;
            }

            function arrayBufferToBase64(buffer) {
                return btoa(String.fromCharCode(...new Uint8Array(buffer)));
            }

            const encoder = new TextEncoder();
            const key = await crypto.subtle.importKey(
                "spki",
                pemToArrayBuffer(publicKeyPEM),
                { name: "RSA-OAEP", hash: "SHA-256" },
                false,
                ["encrypt"]
            );

            let formData = {};
            Form.serializeArray().forEach(field => {
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

            const encryptedPrevenda = await crypto.subtle.encrypt(
                { name: "RSA-OAEP" },
                key,
                encoder.encode(prevenda.toString())
            );
            const prevendaEncrypted = arrayBufferToBase64(encryptedPrevenda);

            $.post('./blocos/participante-atualiza.php', encryptedData, function(data){
                console.log(data);
                $.post('./blocos/lista-vinculados.php', { i: prevendaEncrypted }, function(html) {
                    $('.bloco-vinculados').html(html);
                    $('#formEditaParticipante button[type=submit]').prop('disabled', false);
                    $('#modalEditaParticipante').modal('toggle');
                });
            }).fail(function() {
                alert("Erro ao enviar dados criptografados.");
                $('#formEditaParticipante button[type=submit]').prop('disabled', false);
            });

        } catch (err) {
            console.error("Erro na criptografia:", err);
            alert("Erro ao processar dados com segurança.");
            $('#formEditaParticipante button[type=submit]').prop('disabled', false);
        }
    });




});
</script>
