<div class="modal fade" id="addResponsavelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form action="./adiciona-novo-responsavel" method="post" id="formAddResponsavelModal">
            <div class="modal-header">
                <h4 class="title" id="addResponsavelModalLabel">Pré venda</h4>
            </div>
            <div class="modal-body">
            
                        <h5 class="card-inside-title">Dados do responsável</h5>
                        <div class="row clearfix">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" value="" id="cpf" required maxlength="14" pattern="\d*" />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome</label>                            
                                    <input  id="nome" name="nome" type="text" class="form-control" placeholder="Nome" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefone1" class="form-label">Telefone 1</label>
                                    <input id="telefone1" name="telefone1" type="text" class="form-control" placeholder="Telefone 1" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telfone2" class="form-label">Telefone 2</label>
                                    <input id="telefone2" name="telefone2" type="text" class="form-control" placeholder="Telefone 2" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Email</label>
                                    <input id="email" name="email" type="text" class="form-control" placeholder="Email" value="" />
                                </div>
                            </div> 

                            
                        </div>                       
                        
            </div>
            <div class="modal-footer">
                <input type="hidden" id="idresponsavel" name="idresponsavel" value="">
                <button type="submit" class="btn btn-default btn-round waves-effect">Salvar e avançar</button>
                <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        $('body').on('change', '#cpf', function(){
            let i = $(this).val().replace(/\D/g, '');
            // $(this).val(i);

            $.post('./blocos/procura-responsavel.php', {id:i}, function(data){
                $('form')[0].reset();
                $('#cpf').val(i);
                if (data==0) {
                    // console.log('não existe');
                    $('#idresponsavel').val('');
                } else {
                    let dados = JSON.parse(data);
                    $('#idresponsavel').val(dados[0].id_responsavel);
                    $('#nome').val(dados[0].nome);
                    $('#telefone1').val(dados[0].telefone1);
                    $('#telefone2').val(dados[0].telefone2);
                    $('#email').val(dados[0].email);
                }
            })
        })
    })
</script>

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

    $('input[name="telefone1"]').mask('(00) 0000-00000', {
        onKeyPress: function(val, e, field, options) {
            var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
            $('input[name=telefone1]').mask(mask, options);
        }
    });
    $('input[name="telefone2"]').mask('(00) 0000-00000', {
        onKeyPress: function(val, e, field, options) {
            var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
            $('input[name=telefone2]').mask(mask, options);
        }
    });

    $('#formAddResponsavelModal').on('submit', function(e) {
        $('#formAddResponsavelModal button[type="submit"]').prop('disabled', true);
    })

    $('#addResponsavelModal').on('hidden.bs.modal', function (e) {
        $('#formAddResponsavelModal').trigger('reset');
    })
});
</script>