<?php
session_start();
include_once("./inc/conexao.php");
include_once("./inc/funcoes.php");

if ((!isset($_SESSION['dadosResponsavel'])) || (!$_SESSION['dadosResponsavel']) ) {
    header('Location: /'.$_SESSION['hash_evento']);
}

// include_once("./inc/cad-participantes-regras.php");

$row               = $_SESSION['row'];
$evento_atual      = $_SESSION['evento_atual'];
$id                = $_SESSION['cpf'];
$dados_responsavel = $_SESSION['dadosResponsavel'];
$idPrevendaAtual   = $_SESSION['idPrevenda'];


$sql_secundario = "SELECT * from tbsecundario WHERE ativo=1 and idprevenda = :idPrevenda";
$pre_secundario = $connPDO->prepare($sql_secundario);
$pre_secundario->bindValue(':idPrevenda', $idPrevendaAtual, PDO::PARAM_INT);
$pre_secundario->execute();

if ($pre_secundario->rowCount() > 0) {
    $dados_secundario = $pre_secundario->fetchAll(PDO::FETCH_ASSOC);
} else {
    $dados_secundario = [];
}

$_SESSION['dadosSecundario'] = $dados_secundario;



$sql_prevenda_info = "SELECT * FROM tbprevenda_info WHERE ativo=1 and idprevenda = :idPrevenda";
$pre_prevenda_info = $connPDO->prepare($sql_prevenda_info);
$pre_prevenda_info->bindValue(':idPrevenda', $idPrevendaAtual, PDO::PARAM_INT);
$pre_prevenda_info->execute();

if ($pre_prevenda_info->rowCount() > 0) {
    $row_prevendainfo = $pre_prevenda_info->fetchAll(PDO::FETCH_ASSOC);
} else {
    $row_prevendainfo = [];
}

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

.switch {
  display: inline-block;
  height: 34px;
  position: relative;
  width: 60px;
}

.switch input {
  display:none;
}

.slider {
  background-color: #ccc;
  bottom: 0;
  cursor: pointer;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  transition: .4s;
}

.slider:before {
  background-color: #fff;
  bottom: 4px;
  content: "";
  height: 26px;
  left: 4px;
  position: absolute;
  transition: .4s;
  width: 26px;
}

input:checked + .slider {
  background-color: #66bb6a;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
                    <?= $row[0]['titulo'] . " - Prevenda: " . $_SESSION['idPrevenda'] ?>
                </div>           
               
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    
                    <div class="body">
                        <div class="row">
                            <div class="col-8"><h5>Responsável principal</h5></div>
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
                                        <td>Telefone 1:</td>
                                        <td><?= $dados_responsavel[0]['telefone1'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Telefone 2:</td>
                                        <td><?= ($dados_responsavel[0]['telefone2']==''?'<span style="color: red">Não informado</span>':$dados_responsavel[0]['telefone2']) ?></td>
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
            <div class="col-lg-12">
                <div class="card">
                    
                    <div class="body">
                        <div class="row">
                            <div class="col-8"><h5>Responsável legal secundário (Obrigatório)</h5></div>
                            <div class="col-4 text-end" ><a href="#modalResponsavelLegal" data-target="#modalResponsavelLegal" data-toggle="modal" class="btn btn-primary btn-round">Informa/Editar dados</a></div>
                        </div>
                        <div class="row">
                                <div class="col-12 text-end"></div>
                            </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="" width="100%">
                                    <tr>
                                        <th width="30%">CPF:</th>
                                        <td width="70%"><?= (isset($_SESSION['dadosSecundario'][0]['cpf'])?formatarCPF($_SESSION['dadosSecundario'][0]['cpf']):'<span style="color: red">Preenchimento obrigatório</span>') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nome:</td>
                                        <td><?= (isset($_SESSION['dadosSecundario'][0]['nome'])?$_SESSION['dadosSecundario'][0]['nome']:'<span style="color: red">Preenchimento obrigatório</span>') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Telefone:</td>
                                        <td><?= (isset($_SESSION['dadosSecundario'][0]['telefone'])?$_SESSION['dadosSecundario'][0]['telefone']:'<span style="color: red">Preenchimento obrigatório</span>') ?></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Participante</td>
                                        <td>
                                            <div class="container">
                                                <label class="switch" for="checkbox2">
                                                    <input type="checkbox" id="checkbox2" />
                                                    <div class="slider round"></div>
                                                </label>
                                            </div>
                                        </td>
                                    </tr> -->
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
                                <h5>Informe o meio de pagamento</h5>
                                <p>O pagamento será realizado excluisivamente no caixa, no momento do ingresso ao brinquedo. Para sua comodidade, informe abaixo o meio de pagamento que pretende utilizar.</p>
                                <!-- crie um select com os tipos de pagamento -->
                                <select name="meio_pagamento" class="form-control show-tick p-0" id="meiopgto">
                                    <option value="" <?= (!isset($row_prevendainfo[0]['meiopgto'])) ? 'selected' : '' ?>>Selecione o meio de pagamento</option>
                                    <option value="1" <?= (isset($row_prevendainfo[0]['meiopgto']) && $row_prevendainfo[0]['meiopgto'] == '1') ? 'selected' : '' ?>>Dinheiro</option>
                                    <option value="2" <?= (isset($row_prevendainfo[0]['meiopgto']) && $row_prevendainfo[0]['meiopgto'] == '2') ? 'selected' : '' ?>>Pix</option>
                                    <option value="3" <?= (isset($row_prevendainfo[0]['meiopgto']) && $row_prevendainfo[0]['meiopgto'] == '3') ? 'selected' : '' ?>>Cartão de Crédito</option>
                                    <option value="4" <?= (isset($row_prevendainfo[0]['meiopgto']) && $row_prevendainfo[0]['meiopgto'] == '4') ? 'selected' : '' ?>>Cartão de Débito</option>
                                </select>
                            </div>
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
                                <?php if (empty($_SESSION['dadosSecundario'])) { ?>
                                    <button type="button" data-id="<?= $idPrevendaAtual ?>" data-meiopgto="" data-acao="2" name="" class="btn btn-raised btn-primary waves-effect btn-round" style="background-color: #27ae60!important" disabled>Cadastre o responsável secundário</button>
                                <?php } else { ?>
                                    <button type="button" data-id="<?= $idPrevendaAtual ?>" data-meiopgto="" data-acao="2" name="btnFinaliza" class="btn btn-raised btn-primary waves-effect btn-round btAcao-finaliza" style="background-color: #27ae60!important">Finalizar pré-Cadastro</button>
                                <?php } ?>
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
<script src="./js/funcoes.js?v=<?= filemtime('./js/funcoes.js') ?>"></script>
<script>
    $(document).ready(function(){

        // $('.btAcao-finaliza').data('meiopgto', $('#meiopgto').val());

        //crie uma funcao em jquery onde ao alterar o select de meio de pagamento, ele armazena o valor selecionado em um atributo data-meiopgto do botao de finalizar
        
        $('#meiopgto').on('change', function() {
            let meioPagamento = $(this).val();
            // $('.btAcao-finaliza').data('meiopgto', meioPagamento);
            alert("Meio de pagamento selecionado: " + meioPagamento);
        });
        
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

        $('form#formResponsavelLegal').on('input change', function(){
            $('.btsalvaLegal').attr('disabled', false);            
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
                console.log(data);
            });
        });






         $('form#formResponsavelLegal').submit(async function(e) {
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

            // Criptografa os campos (input, textarea, select, hidden)
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
            $.post('./blocos/atualiza-responsavel-legal.php', encryptedFields, function(data) {
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

        $('input[name^="telefone"]').mask('(00) 0000-00000', {
            onKeyPress: function(val, e, field, options) {
                var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
                field.mask(mask, options);
            }
        });
    });


</script>



</body>
</html>