<div class="modal fade" id="addResponsavelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form action="./adiciona-novo-responsavel" method="post" id="formAddResponsavelModal">
            <div class="modal-header">
                <h4 class="title" id="addResponsavelModalLabel">Pré venda</h4>
            </div>
            <div class="modal-body">
            
                <h5 class="card-inside-title">Dados do responsávelYY</h5>
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

<script src="./js/funcoes.js"></script>
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


    $(document).ready(function(){
        
        /*
        $('body').on('change', '#cpf', function(){
            
            let i = $(this).val().replace(/\D/g, '');

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

        */
        

$('body').on('change', '#cpf', async function() {
    let i = $(this).val().replace(/\D/g, '');

    try {
        // Importa a chave pública
        const key = await crypto.subtle.importKey(
            "spki",
            pemToArrayBuffer(publicKeyPEM),
            { name: "RSA-OAEP", hash: "SHA-256" },
            false,
            ["encrypt"]
        );

        const encoder = new TextEncoder();
        const encrypted = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            key,
            encoder.encode(i)
        );

        const encryptedId = arrayBufferToBase64(encrypted);

        $.post('./blocos/procura-responsavel.php', { id: encryptedId }, function(data) {
            $('form')[0].reset();
            $('#cpf').val(i); // Mostra o CPF original no campo
            console.log(data);

            if (data == 0) {
                $('#idresponsavel').val('');
            } else {
                let dados = JSON.parse(data);
                $('#idresponsavel').val(dados[0].id_responsavel);
                $('#nome').val(dados[0].nome);
                $('#telefone1').val(dados[0].telefone1);
                $('#telefone2').val(dados[0].telefone2);
                $('#email').val(dados[0].email);
            }
        });
    } catch (err) {
        console.error('Erro ao criptografar o CPF:', err);
        alert('Erro ao processar o CPF.');
    }
});




        /*
       $('body').on('change', '#cpf', async function () {
            let i = $(this).val().replace(/\D/g, '');

            try {
                const key = await crypto.subtle.importKey(
                    "spki",
                    pemToArrayBuffer(publicKeyPEM),
                    { name: "RSA-OAEP", hash: "SHA-256" },
                    false,
                    ["encrypt"]
                );

                const encoder = new TextEncoder();
                const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, key, encoder.encode(i));
                const cpfCriptografado = arrayBufferToBase64(encrypted);

                $.post('./blocos/procura-responsavel.php', { id_seguro: cpfCriptografado }, function (data) {
                    $('form')[0].reset();
                    $('#cpf').val(i); // Mantém o CPF visível no input

                    if (data == 0) {
                        $('#idresponsavel').val('');
                    } else {
                        let dados = JSON.parse(data);
                        $('#idresponsavel').val(dados[0].id_responsavel);
                        $('#nome').val(dados[0].nome);
                        $('#telefone1').val(dados[0].telefone1);
                        $('#telefone2').val(dados[0].telefone2);
                        $('#email').val(dados[0].email);
                    }
                });

            } catch (err) {
                console.error('Erro na criptografia do CPF:', err);
                alert('Não foi possível criptografar o CPF. Verifique sua chave pública.');
            }
        });
        */


        /*
    })
</script>

<script>
    $(document).ready(function() {
    // Função para aplicar a máscara de CPF

    */

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
/*
    $('#formAddResponsavelModal').on('submit', function(e) {
        $('#formAddResponsavelModal button[type="submit"]').prop('disabled', true);
    })
*/




// Manipulação do submit
document.querySelector('#formAddResponsavelModal').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    try {
        const publicKey = await crypto.subtle.importKey(
            "spki",
            pemToArrayBuffer(publicKeyPEM),
            { name: "RSA-OAEP", hash: "SHA-256" },
            false,
            ["encrypt"]
        );

        const encoder = new TextEncoder();
        const encryptedFields = {};

        const inputs = form.querySelectorAll('input[name], textarea[name]');
        for (let input of inputs) {
            const name = input.name;
            const value = input.value;
            if (!name || value === '') continue;

            const encrypted = await crypto.subtle.encrypt({ name: "RSA-OAEP" }, publicKey, encoder.encode(value));
            encryptedFields[name + '_seguro'] = arrayBufferToBase64(encrypted);
        }

        // Cria novo form invisível com os dados criptografados
        const newForm = document.createElement('form');
        newForm.method = 'POST';
        newForm.action = form.action;
        newForm.style.display = 'none';

        for (let [name, value] of Object.entries(encryptedFields)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            newForm.appendChild(input);
        }

        document.body.appendChild(newForm);
        newForm.submit(); // Envia como form normal (recarrega a página)
    } catch (err) {
        alert("Erro ao criptografar os dados. " + err); 
        console.error("Erro na criptografia: ", err);
        submitBtn.disabled = false;
    }
});



/* ************************* */



    $('#addResponsavelModal').on('hidden.bs.modal', function (e) {
        $('#formAddResponsavelModal').trigger('reset');
    })
});
</script>