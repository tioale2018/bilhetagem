<?php
require '../../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

// Lê a chave privada
$privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents(__DIR__ . '/../../chaves/chave_privada.pem'))
    ->withPadding(RSA::ENCRYPTION_OAEP)
    ->withHash('sha256');
/*
// Decodifica a senha criptografada
if (isset($_POST['id_prevenda_seguro'])) {
    $encrypted_i      = base64_decode($_POST['id_prevenda_seguro'] ?? '');
} else {
    $encrypted_i      = base64_decode($_POST['i'] ?? '');
}
*/

// $participante = intval($_POST['p']);
// $prevenda     = intval($_POST['e']);

$encrypted_participante      = base64_decode($_POST['p'] ?? '');
$encrypted_prevenda         = base64_decode($_POST['e'] ?? '');
// $idprevenda = intval($_POST['i']);

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

/*
    $('#formEditaParticipante').submit(function(e){
        e.preventDefault();
        let Form = $(this);
        let idPrevenda = $('input[name="idprevenda"]').val();

        var dateInput = $('#nasc').val();
        if (!isValidDate(dateInput)) {
            
            $('#nasc').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('#nasc').focus();
        } else {

              $.post('./blocos/participante-atualiza.php', Form.serialize(), function(data){

                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:idPrevenda }, function(){
                    $('#modalEditaParticipante').modal('toggle'); 
                });
            });
        }        
    });

    */


    $('#formEditaParticipante').submit(async function (e) {
        e.preventDefault();

        const form = $(this);
        const idPrevenda = $('input[name="idprevenda"]').val();
        const dateInput = $('#nasc').val();

        if (!isValidDate(dateInput)) {
            $('#nasc').val('');
            alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
            $('#nasc').focus();
            return;
        }

        // Garante que a chave pública está definida
        if (typeof publicKeyPEM === 'undefined') {
            console.error("Chave pública não definida.");
            return;
        }

        try {
            const encoder = new TextEncoder();
            const pemContents = publicKeyPEM.replace(/-----.*?-----/g, "").replace(/\s/g, "");
            const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));

            const publicKey = await crypto.subtle.importKey(
                "spki",
                binaryDer.buffer,
                { name: "RSA-OAEP", hash: "SHA-256" },
                false,
                ["encrypt"]
            );

            // Constrói o objeto com todos os campos do formulário
            const formDataObj = {};
            form.serializeArray().forEach(item => {
                formDataObj[item.name] = item.value;
            });

            // Adiciona manualmente o idPrevenda (caso queira garantir que está incluído)
            formDataObj['idprevenda'] = idPrevenda;

            // Converte em JSON e criptografa
            const jsonStr = JSON.stringify(formDataObj);
            const encoded = encoder.encode(jsonStr);

            const encryptedBuffer = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, publicKey, encoded);
            const encryptedBase64 = btoa(String.fromCharCode(...new Uint8Array(encryptedBuffer)));

            // Envia os dados criptografados
            $.post('./blocos/participante-atualiza.php', { payload: encryptedBase64 }, function (data) {
                // Recarrega os participantes vinculados e fecha o modal
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: idPrevenda }, function () {
                    $('#modalEditaParticipante').modal('toggle');
                });
            });

        } catch (err) {
            console.error("Erro ao criptografar dados do formulário:", err);
        }
    });




});
</script>

