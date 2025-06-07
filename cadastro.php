<?php
session_start();

if ((!isset($_SESSION['dadosResponsavel'])) || (!$_SESSION['dadosResponsavel']) ) {
    header('Location: /'.$_SESSION['hash_evento']);
}

// include_once("./inc/cad-participantes-regras.php");

$row               = $_SESSION['row'];
$evento_atual      = $_SESSION['evento_atual'];
$id                = $_SESSION['cpf'];
$dados_responsavel = $_SESSION['dadosResponsavel'];
$idPrevendaAtual   = $_SESSION['idPrevenda'];

?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php

include_once("./inc/head.php");
include_once("./inc/funcoes.php");

?>
<style>
.invalid {
    border: 2px solid red;
}
.menu-bottom {
    bottom: 0;
    left: 0;
    position: fixed;
    width: 100%;
    height: 50px;
    background-color: #abc;
    z-index: 999;
    display: flex;
}
</style>
</head>
<body class="theme-black">
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="assets/images/logo.svg" width="48" height="48" alt="Alpino"></div>
        <p>Aguarde</p>        
    </div>
</div>

<section class="content mt-2">    
    <div class="container">
        <div class="block-header mb-2 p-0">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Pré cadastro</h2>                    
                </div> 
                <div class="col-12">
                    <?= $row[0]['titulo'] ?>
                </div>           
               
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    
                    <div class="body">
                        <div class="row">
                            <div class="col-8"><h5>Dados do responsável</h5></div>
                            <div class="col-4 text-end" ><a href="#modalEditaResp" data-target="#modalEditaResp" data-toggle="modal" class="btn btn-primary btn-round">Editar dados</a></div>
                        </div>
                        <div class="row">
                                <div class="col-12 text-end"></div>
                            </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="" width="100%">
                                    <tr>
                                        <th width="30%">CPF:</th>
                                        <td width="70%"><?= formatarCPF($id) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nome:</td>
                                        <td><?= $dados_responsavel[0]['nome'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td><?= $dados_responsavel[0]['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Telefone:</td>
                                        <td><?= $dados_responsavel[0]['telefone1'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Telefone:</td>
                                        <td><?= $dados_responsavel[0]['telefone2'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>E-mail:</td>
                                        <td><?= $dados_responsavel[0]['email'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                         <!-- <h2> </h2> -->
                          <div style="text-align:center">
                          <a href="#modalResponsavelLegal" data-toggle="modal" class="btn btn-primary btn-round btn-block" data-target="#modalResponsavelLegal" style="background-color:rgb(213, 28, 28)!important">Adicionar Responsável Legal secundário (obrigatório)</a>
                          </div>

                          <div style="text-align:center">
                          <a href="#modalAddParticipante" data-toggle="modal" class="btn btn-primary btn-round btn-block" data-target="#modalAddParticipante" style="background-color: #27ae60!important">Adicionar participante</a>
                          </div>
                        
                    </div>
                    
                    <div class="body">
                        <h5>Participantes cadastrados</h5>
                        <div class="table-responsive bloco-vinculados">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        
        <div class="clearfix">
            <div class="">
                <div class="card">
                
                    <div class="body">
                        <div class="row">
                            <div class="col-12">
                            <?= $row[0]['regras_cadastro'] ?>
                            </div>
                        </div>
                        
                        <div class="row justify-content-end">
                             <div class="col-md-3">
                                <button type="button" data-id="<?= $idPrevendaAtual ?>" data-acao="1" name="btnCancela" class="btn btn-raised btn-danger waves-effect btn-round btAcao-cancela">Cancelar pré-cadastro</button>
                            </div>

                            <div class="col-md-3">
                                <button type="button" data-id="<?= $idPrevendaAtual ?>" data-acao="2" name="btnFinaliza" class="btn btn-raised btn-primary waves-effect btn-round btAcao-finaliza" style="background-color: #27ae60!important">Finalizar pré-Cadastro</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<input type="hidden" id="idPrevendaAtual" data-id-idprevenda="<?= $idPrevendaAtual ?>" />
<div data-idtoken="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" id="idcsrftoken" styte="display: none"></div>
<div id="hashevento" data-id-hashevento="<?= $_SESSION['hash_evento'] ?>" style="display: none"></div>

<?php include('./inc/cad-participante-modal.php') ?>
<?php include('./inc/cad-responsavel-modal.php') ?>

<?php include('./inc/cadastro-editaresp-modal.php') ?>
<?php include('./inc/javascript.php') ?>
<script src="./js/safe.js?t=<?= filemtime('./js/safe.js') ?>"></script>
<script>
    $(document).ready(function(){
        
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

        let idPrevendaAtual = $('#idPrevendaAtual').data('id-idprevenda');

        // $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i: idPrevendaAtual });

        async function carregarListaVinculadosCriptografado(idPrevendaAtual) {
            if (typeof crypto === 'undefined' || !crypto.subtle) {
                alert("Navegador não suporta criptografia segura.");
                return;
            }

            try {
                const pemContents = publicKeyPEM.replace(/-----.*?-----/g, "").replace(/\s/g, "");
                const binaryDer = Uint8Array.from(atob(pemContents), c => c.charCodeAt(0));
                const key = await crypto.subtle.importKey(
                    "spki",
                    binaryDer.buffer,
                    { name: "RSA-OAEP", hash: "SHA-256" },
                    false,
                    ["encrypt"]
                );

                const encoder = new TextEncoder();
                const encrypted = await crypto.subtle.encrypt(
                    { name: "RSA-OAEP" },
                    key,
                    encoder.encode(idPrevendaAtual.toString())
                );

                const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));

                // Envia o ID criptografado
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', { i: encoded });

            } catch (error) {
                console.error("Erro ao criptografar o ID da pré-venda:", error);
            }
        }

        carregarListaVinculadosCriptografado(idPrevendaAtual);

        
        $('form#formResponsavel').on('input change', function(){
            $('.btsalvar').attr('disabled', false);            
        });

        $('form#formResponsavel').submit(async function(e) {
            e.preventDefault();

            const form = this;

            // Adiciona o CSRF token manualmente no formulário antes de criptografar
            if (!form.querySelector('input[name="csrf_token"]')) {
                const token = $('#csrf_token').data('idtoken'); 
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "csrf_token";
                input.value = token;
                form.appendChild(input);
            }

            // Importa a chave pública no formato PEM (presume que `publicKeyPEM` já esteja definida)
            const key = await crypto.subtle.importKey(
                "spki",
                pemToArrayBuffer(publicKeyPEM),
                { name: "RSA-OAEP", hash: "SHA-256" },
                false,
                ["encrypt"]
            );

            const encoder = new TextEncoder();
            const encryptedFields = {};

            // Criptografa os campos visíveis (input, textarea, select)
            const visibleFields = form.querySelectorAll("input:not([type='hidden'])[name], textarea[name], select[name]");
            for (const input of visibleFields) {
                if (!input.name || input.disabled) continue;

                const encrypted = await crypto.subtle.encrypt(
                    { name: "RSA-OAEP" },
                    key,
                    encoder.encode(input.value)
                );

                const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
                encryptedFields[input.name + "_seguro"] = encoded;
            }

            // Inclui os campos ocultos (como o CSRF) sem criptografia
            const hiddenFields = form.querySelectorAll('input[type="hidden"][name]');
            for (const input of hiddenFields) {
                encryptedFields[input.name] = input.value;
            }

            // Envia via POST para o backend
            $.post('./blocos/atualiza-responsavel.php', encryptedFields, function(data) {
                console.log(data);
                swal({
                    title: "Dados salvos",
                    text: "Os dados informados foram salvos com sucesso!",
                    type: "success",
                    showCancelButton: false,
                    closeOnConfirm: true
                }, function () {
                    location.reload();
                });
            }).fail(function(xhr, status, error) {
                console.error('Erro ao enviar os dados criptografados:', error);
                alert('Erro ao enviar os dados criptografados.');
            });
        });


/*
        $('.btAcao-cancela').on('click', function(){
            let id = $(this).data('id');
            let idHash = $('#hashevento').data('id-hashevento');

            swal({
                title: "Cancelar pré-cadastro?",
                text: "Deseja realmente cancelar e excluir esta pré-cadastro?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#27ae60",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post('./blocos/reserva-cancela.php', {i:id}, function(data){
                        location.href="/"+idHash;
                    });
                }
            })
        })

        */

        $('.btAcao-cancela').on('click', async function () {
            let id = $(this).data('id');
            let idHash = $('#hashevento').data('id-hashevento');

            try {
                const encoder = new TextEncoder();
                const key = await crypto.subtle.importKey(
                    "spki",
                    pemToArrayBuffer(publicKeyPEM),
                    { name: "RSA-OAEP", hash: "SHA-256" },
                    false,
                    ["encrypt"]
                );

                const encryptedId = await crypto.subtle.encrypt(
                    { name: "RSA-OAEP" },
                    key,
                    encoder.encode(id.toString())
                );

                const encryptedIdBase64 = arrayBufferToBase64(encryptedId);

                swal({
                    title: "Cancelar pré-cadastro?",
                    text: "Deseja realmente cancelar e excluir esta pré-cadastro?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#27ae60",
                    confirmButtonText: "Sim",
                    cancelButtonText: "Não",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.post('./blocos/reserva-cancela.php', { i: encryptedIdBase64 }, function (data) {
                            location.href = "/" + idHash;
                        }).fail(function () {
                            alert("Erro ao enviar solicitação. Verifique a conexão.");
                        });
                    }
                });
            } catch (err) {
                console.error("Erro ao criptografar o ID:", err);
                alert("Erro de segurança ao processar o cancelamento.");
            }
        });

        $('body').on('click', '.btAcao-finaliza', function(){
            let id = $(this).data('id');

            swal({
                title: "Finalizar e enviar pré-cadastro?",
                text: "Deseja concluir e enviar o pré-cadastro?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#27ae60",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    location.href="reserva-resumo";   
                }
            })
        });

        $('#formCancela').submit(function(e){
            if(!confirm('Deseja cancelar o cadastro?')) {
                e.preventDefault();
            }
        })

        $('input[name=telefone1]').mask('(00) 0000-00000', {
            onKeyPress: function(val, e, field, options) {
                var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
                $('input[name=telefone1]').mask(mask, options);
            }
        });

        $('input[name=telefone2]').mask('(00) 0000-00000', {
            onKeyPress: function(val, e, field, options) {
                var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
                $('input[name=telefone2]').mask(mask, options);
            }
        });
    });


</script>



</body>
</html>