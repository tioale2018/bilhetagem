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
$encrypted_i      = base64_decode($_POST['i'] ?? '');
// $idprevenda = intval($_POST['i']);

try {
    $idprevenda    = $privateKey->decrypt($encrypted_i);
} catch (Exception $e) {
    die ("Erro ao descriptografar: " . $e->getMessage());
}

// echo "responsavale: ".$_SESSION['dadosResponsavel'][0]['id_responsavel'];

if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

session_start();

include('../inc/conexao.php');
include('../inc/funcoes.php');


$sql = "SELECT tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbvinculo.descricao as tipovinculo, tbvinculados.lembrar, tbentrada.id_pacote, tbperfil_acesso.titulo as perfil, tbprevenda.id_evento, tbentrada.id_prevenda,  tbentrada.autoriza
FROM tbentrada
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbvinculo on tbvinculados.tipo=tbvinculo.id_vinculo
inner join tbperfil_acesso on tbperfil_acesso.idperfil=tbentrada.perfil_acesso
inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
inner join tbresponsavel on tbvinculados.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda and tbresponsavel.id_responsavel=".$_SESSION['dadosResponsavel'][0]['id_responsavel']." order by nome";
// WHERE tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:idprevenda order by nome";

$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();
$row = $pre->fetchAll();

$rowNum = $pre->rowCount();


// Exemplo de uso
// $dataNascimento = "1990-07-15";
// echo "Idade: " . calcularIdade($dataNascimento) . " anos";
?>

<div class="body lista-vinculados">
    <div class="table-responsive">
        <?php 
        $travaBtEnvia = 'false';
        $textoBtEnvia = 'Finalizar pré-cadastro';
        $textoBtErro = 'Autorizações pendentes';
        if ($pre->rowCount()>0) { 
        ?>
                
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <!-- <th>Nascimento</th> 
                    <th>Vínculo</th>
                    <th>Lembrar</th>
                    <th>Perfil</th> -->
                    <th>Autorizar</th>
                    <th>Edita/Exclui</th>
                </tr>
            </thead>                                
            <tbody>

            <?php  
            foreach ($row as $key => $value) { ?>
               
                <tr>
                    <td>
                        <div><?= $row[$key]['nome'] ?></div>
                        <div><?= calcularIdade($row[$key]['nascimento']) ?> Anos</div>
                    </td>
                    <!-- <td><?= date('d/m/Y', strtotime($row[$key]['nascimento'])) ?></td> -->
                    <!-- <td><span class="badge badge-success"><?= $row[$key]['tipovinculo'] ?></span></td>
                    <td><span class="badge badge-success"><?= ($row[$key]['lembrar']==1?'Sim':'Não') ?></span></td> -->
                    <!-- <td><?= $row[$key]['perfil'] ?></td> -->
                    <td class="text-center">
                        <?php 
                            if ($row[$key]['autoriza']==0) {
                                $travaBtEnvia = 'true';
                                $textoBtEnvia = $textoBtErro;
                                echo '<a href="#" class="btnModalTermoParticipante" data-id="'.$row[$key]['id_entrada'].'"><span class="badge badge-danger">Autorizar</span></a>';
                            } else {
                                echo '<span class="badge badge-success">Autorizado</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <button data-idprevenda="<?= $row[$key]['id_prevenda'] ?>" data-idparticipante="<?= $row[$key]['id_vinculado'] ?>" class="btn btn-icon btn-neutral btn-icon-mini margin-0 btnModalEditaParticipante"><i class="zmdi zmdi-edit"></i></button>
                        <button data-entrada="<?= $row[$key]['id_entrada'] ?>" class="btn btn-icon btn-neutral btn-icon-mini margin-0 excluivinculo"><i class="zmdi zmdi-delete"></i></button>
                    </td>
                </tr>
                
             <?php } ?>   
            </tbody>
        </table>

        <?php } else { 
            $travaBtEnvia = 'true';
            $textoBtEnvia = $textoBtErro;
            ?>

            <div class="alert alert-danger">
                <strong>Opa!</strong> Nenhuma criança foi vinculada a este cadastro.
            </div>

        <?php } ?>
    </div>
</div>

<?php if (isset($key)) { ?>

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
    $(document).ready(function(){
/*
        $('body').on('click', '.btnModalEditaParticipante', function() {
            let i = $(this).data('idparticipante');
            let j = $(this).data('idprevenda');
            
            $('#modalEditaParticipante').modal();
            $('#formParticipanteContent').load('./blocos/edita-participante.php', {p:i, e:j});
        });
*/

$('body').on('click', '.btnModalEditaParticipante', async function () {
    const idParticipante = $(this).data('idparticipante');
    const idPrevenda = $(this).data('idprevenda');

    try {
        // Garante que a chave pública está definida
        if (typeof publicKeyPEM === 'undefined') {
            console.error("Chave pública não está definida.");
            return;
        }

        const encoder = new TextEncoder();

        // Converte PEM para DER binário
        const pemContents = publicKeyPEM.replace(/-----.*?-----/g, "").replace(/\s/g, "");
        const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));

        const publicKey = await crypto.subtle.importKey(
            "spki",
            binaryDer.buffer,
            { name: "RSA-OAEP", hash: "SHA-256" },
            false,
            ["encrypt"]
        );

        // Criptografa os dois valores
        const encryptedIdParticipante = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, publicKey, encoder.encode(idParticipante.toString()));
        const encryptedIdPrevenda = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, publicKey, encoder.encode(idPrevenda.toString()));

        // Converte para Base64
        const encodedP = btoa(String.fromCharCode(...new Uint8Array(encryptedIdParticipante)));
        const encodedE = btoa(String.fromCharCode(...new Uint8Array(encryptedIdPrevenda)));

        // Exibe modal e carrega conteúdo com dados criptografados
        $('#modalEditaParticipante').modal();

        // Usa jQuery.ajax para permitir envio via POST
        $.post('./blocos/edita-participante.php', { p: encodedP, e: encodedE }, function (data) {
            $('#formParticipanteContent').html(data);
        });

    } catch (err) {
        console.error("Erro ao criptografar os parâmetros:", err);
    }
});




        $('body').on('click', '.btnModalTermoParticipante', async function(e) {
            e.preventDefault();

            let id = $(this).data('id');

            if (typeof encryptRSA !== "function") {
                alert("Função de criptografia não disponível.");
                return;
            }

            try {
                const encryptedID = await encryptRSA(id.toString(), publicKeyPEM);

                $('#modalTermoParticipante').modal();
                $('#formTermoParticipante').load('./blocos/participante-termo-modal.php', { i: encryptedID });

            } catch (error) {
                console.error("Erro ao criptografar o ID:", error);
            }
        });


        $('.excluivinculo').on('click', function(){
            let p = $(this).data('entrada');
            
            swal({
                title: 'Exclusão',
                text: 'Deseja realmente excluir este participante?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#27ae60",
                cancelButtonColor: "#DD6B55",
                closeButtonColor: "#DD6B55",
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post('./blocos/participante-exclui.php', {i:p}, function(data){
                        swal({
                            title: "Operação realizada com sucesso",
                            text: "Mensagem de agradecimento",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok",
                            closeOnConfirm: true
                        }, function () {
                            $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $row[$key]['id_prevenda'] ?> });
                        });
                    });
                }

            });
        });

    });   
</script>

<?php } ?>

<script>
    $('select').selectpicker();
    $('button[name=btnFinaliza]').prop('disabled', <?= $travaBtEnvia ?>).text('<?= $textoBtEnvia ?>');
</script>