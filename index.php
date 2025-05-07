<?php
/*
session_set_cookie_params([
    'lifetime' => 0, // Cookie de sessão (expira quando o navegador é fechado)
    'path' => '/', // Disponível em todo o domínio
    'domain' => $_SERVER['HTTP_HOST'], // Define dinamicamente o domínio
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', // Apenas via HTTPS
    'httponly' => true, // Impede acesso via JavaScript
    'samesite' => 'Strict' // Proteção contra CSRF
]);
*/
?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php include('./inc/head.php') ?>
<link rel="stylesheet" href="./libs/sweetalert2/dist/sweetalert2.css">
<style>
    .swal2-modal {
        text-align: left !important;
        justify-content: left !important;
        align-items: left !important;
    }
</style>
<?php include('./inc/conexao.php') ?>
<?php 
function buscarPorHash($array, $hash) {
    // Percorre o array principal
    foreach ($array as $item) {
        // Verifica se o valor do hash corresponde ao hash informado
        if (isset($item['hash']) && $item['hash'] === $hash) {
            // Retorna o item correspondente
            return $item;
        }
    }
    // Se nenhum item for encontrado, retorna null ou uma mensagem de erro
    return null;
}

$sql = "select tbevento_ativo.hash, tbevento_ativo.idevento, tbevento.titulo, tbevento.local, tbevento.modo_pgto, tbevento.regras_home, tbevento.regras_cadastro, tbevento.regras_parque, tbevento.regras_comunica
from tbevento_ativo 
inner join tbevento on tbevento_ativo.idevento=tbevento.id_evento
where tbevento_ativo.ativo=1 and tbevento.status=2";
$pre = $connPDO->prepare($sql);
$pre->execute();
$row = $pre->fetchAll();

foreach ($row as $key => $value) {
    $lista_hash[] = $row[$key][0];
}

if ( !isset($_GET['i']) || !in_array($_GET['i'],$lista_hash)) {
    header("Location: 404.php");
}

$dadosEvento = buscarPorHash($row, $_GET['i']);

?>
<style>
    .logo-brand {
        margin: 0 0 20px 0;
    }

    .logo-brand img {
        width: 250px;
        height: auto;
    }
</style>

</head>

<body class="theme-black">
<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <!-- <div class="row">
                <div class="col-12">
                    <div class="company_detail">
                        <div class="logo-brand"><img src="./img/logo-multi2.png" alt="" height="100"></div>
                    </div>
                </div>
            </div> -->
            <div class="row flex-md-row-reverse">
            <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <h5>Preencha o cadastro</h5>
                            <p>Acesse aqui as <a href="" class="regrasparque">regras do parque</a></p>
                            <p class="mt-1"><span>Informe abaixo os dados do responsável pelos participantes</span></p>
                        </div>

                        <div class="area-form-index">
                            <form class="form" method="post" action="" id="form-busca-cpf">                            
                                <div class="input-group">
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" maxlength="14" pattern="\d*" required>
                                    <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                                </div>
                            
                                <div class="footer">
                                    <input type="hidden" name="hashevento" value="<?= htmlspecialchars($_GET['i']) ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit" class="btn btn-primary btn-round btn-block">Continuar</button>
                                </div>
                            </form>

                        </div>
                        
                        
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h3><?= @$dadosEvento['titulo'] ?></h3>
                        <p>Realize o seu cadastro aqui para facilitar o procedimento de acesso ao parque.</p>                        
                        
                    </div>                    
                </div>
                
            </div>
        </div>
    </div>
</div>



<!-- Jquery Core Js -->
<!-- <script src="assets/bundles/libscripts.bundle.js"></script> -->
 <!-- Lib Scripts Plugin Js --> 
<!-- <script src="assets/bundles/vendorscripts.bundle.js"></script>  -->
<!-- <script src="./assets/plugins/sweetalert/sweetalert.min.js"></script>  -->
 <script src="./js/safe.js"></script>
 <script src="./libs/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- SweetAlert Plugin Js --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>

    $(document).ready(function(){
        

        $('body').on('click', '#regras', function(e){
            e.preventDefault();
            
            Swal.fire({
                title: 'Termos de uso dos dados',
                html: '<?= @$dadosEvento['regras_home'] ?>'
            });
        });

        $('body').on('click','.regrasparque', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Regras do parque',
                html: '<?= @$dadosEvento['regras_parque'] ?>'
            });
        });

        $('body').on('click','#regrascomunica', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Política de Autorizacão para Comunicação e Compartilhamento de Dados',
                html: '<?= @$dadosEvento['regras_comunica'] ?>'
            });
        });

        $('#form-busca-cpf').on('submit', function(e) {
            e.preventDefault();
            let dadosForm = $(this).serialize();

            $.post('./form-index.php', dadosForm, function(response) {
                $('.area-form-index').html(response);
            }).fail(function(xhr) {
                console.log("Ocorreu um erro: " + xhr.status + " " + xhr.statusText);
            });

        });

        $('body').on('submit', '#form-busca-reserva', function(e) {
           
            if (!$('input[name=termos]').is(':checked')) {
                e.preventDefault(); // Impede o envio do formulário
                alert('Por favor, leia e aceite os termos de uso antes de continuar.');
            }

            let campoNome = $('input[name="nome"]').val();
            //  alert(validarNomeSobrenome(campoNome));

            //e.preventDefault(); // Impede o envio do formulário

            if (!validarNomeSobrenome(campoNome)) {
                    e.preventDefault(); // Impede o envio do formulário
                    $('#erro-nome').show();
                }
  
        });

    })
    
</script>

<script>
/*
    $(document).ready(function() {
    // Função para aplicar a máscara de CPF
    function aplicarMascaraCPF(cpf) {
        return cpf
            .replace(/\D/g, '') // Remove caracteres não numéricos
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    // Validação do CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, ''); // Remove caracteres não numéricos
        
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false; // Verifica se o CPF tem 11 dígitos e não é uma sequência de números iguais
        }

        let soma = 0;
        let resto;

        for (let i = 1; i <= 9; i++) {
            soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }
        if (resto !== parseInt(cpf.charAt(9))) {
            return false;
        }

        soma = 0;
        for (let i = 1; i <= 10; i++) {
            soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }
        return resto === parseInt(cpf.charAt(10));
    }

    // Máscara e validação do CPF no campo de entrada
    $('input[name="cpf"]').on('input', function() {
        let cpf = $(this).val();
        $(this).val(aplicarMascaraCPF(cpf));
        
        // Validação do CPF
        if (!validarCPF(cpf.replace(/\D/g, ''))) {
            $(this).css('border', '2px solid red'); // Borda vermelha se o CPF for inválido
            $('button[type="submit"]').prop('disabled', true); // Impede o submit
        } else {
            $(this).css('border', ''); // Reseta a borda
            $('button[type="submit"]').prop('disabled', false); // Permite o submit
        }
    });

    // Reseta a borda ao corrigir o CPF
    $('input[name="cpf"]').on('focus', function() {
        $(this).css('border', '');
        let cpf = $(this).val().replace(/\D/g, '');
        $(this).val(aplicarMascaraCPF(cpf));
    });

    // Remove a máscara ao perder o foco
    $('input[name="cpf"]').on('blur', function() {
        let cpf = $(this).val().replace(/\D/g, '');
        $(this).val(cpf);
    });

  

    
});
*/
</script>



</body>
</html>