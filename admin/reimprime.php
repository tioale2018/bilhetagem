<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>

</head>
<body class="theme-black">
<?php include('./inc/pageloader.php') ?>

<?php include_once('./inc/menu-overlay.php') ?>

<?php include('./inc/menu_topo.php') ?>
<?php include('./inc/menu_principal.php') ?>
<?php include('./inc/menu_lateral.php') ?>

<section class="content">
    <div class="container">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <h2>Re-impressão</h2>                    
                </div> 
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2>Re-impressão de comprovante de entrada</h2>
                        
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <form action="" method="post" id="formreimpressao">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="cpf" class="form-label">CPF</label>
                                        <input name="cpf" type="text" class="form-control" placeholder="CPF" value="" id="cpf" required maxlength="14" pattern="\d*" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-default btn-round waves-effect" disabled>Localizar</button>
                                    </div>
                                </div> 
                            </form>

                        </div>
                    </div>
                   
                </div>
            </div>


            <div class="col-lg-12 col-md-12 lista-entradas">
                
            </div>

        </div> 
    </div>
</section>

<?php include('./inc/javascript.php') ?>

<script>
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


   $('#formreimpressao').submit(function(e) {
        e.preventDefault();
        let url = './blocos/reimprime-lista.php';
        let dados = $(this).serialize();
        $.post(url, dados, function(data){
            $('.lista-entradas').html(data);
        });
    })



});
</script>

</body>
</html>