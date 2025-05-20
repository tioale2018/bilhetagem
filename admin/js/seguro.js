/*
const publicKeyPEM = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0BxUXjrrGvXDCIplSQ7l
XfPN1PHujl9CTumnjnM58/2vCtkEaqNbVMXbqhFbqSIpbd1J2k6nn9QMyEvA2uLe
kVgQhMBhxtxFNnuMYWJAeLddas1+Vhn5jygLhdk+PxZSXi/ZKrrCqq1QwA+PSeRq
aL4StVkBNCaxXRElxWXjsPVm0JUgXAuAfzBwGeKwelSUjgoTAmTLcNOOxDL+LGYD
x7IM5PjofaiJwLj3oQpkcfsxvDZ3SMpj/Jo+V+i8OBQwCyVOAfOEvUN+O1YZlBUT
LcM7KvDLMtcQyGf//3QsjLsfqa/XEAvdAISjHO5TNAXy9MXPiEwd1cPyis7toz/d
mQIDAQAB
-----END PUBLIC KEY-----`;

    $('#form-busca-cpf').on('submit', async function(e) {
        e.preventDefault();

        const form = this;

        try {
            const encryptedData = await window.encryptFormFields(form, publicKeyPEM);
            if (!encryptedData) return;

            $.post('./form-index.php', encryptedData, function(response) {
                $('.area-form-index').html(response);
            }).fail(function(xhr) {
                console.log("Ocorreu um erro: " + xhr.status + " " + xhr.statusText);
            });

        } catch (error) {
            console.error("Erro ao criptografar:", error);
        }
    });
*/

/*

$('body').on('submit', '#form-busca-reserva', async function(e) {
    e.preventDefault();

    // Verifica se os termos foram aceitos
    if (!$('input[name=termos]').is(':checked')) {
        alert('Por favor, leia e aceite os termos de uso antes de continuar.');
        return;
    }

    // Validação de nome completo
    let campoNome = $('input[name="nome"]').val();
    if (!validarNomeSobrenome(campoNome)) {
        $('#erro-nome').show();
        return;
    }

    const form = this;

    // Verifica se a função de criptografia está carregada
    if (typeof encryptFormFields !== "function") {
        alert("Função de criptografia não encontrada. Verifique se o safe.js foi carregado.");
        return;
    }

    try {
        // Criptografa os dados do formulário
        const encryptedData = await encryptFormFields(form, publicKeyPEM);
        if (!encryptedData) return;

        // Remove campos ocultos antigos (se existirem)
        $(form).find('input.encrypted').remove();

        // Adiciona campos criptografados como inputs ocultos
        for (const [name, value] of Object.entries(encryptedData)) {
            $('<input>', {
                type: 'hidden',
                name: name,
                value: value,
                class: 'encrypted' // marca para futura limpeza, se necessário
            }).appendTo(form);
        }

        // Desativa os campos originais para não serem enviados
        $(form).find('input, textarea, select').each(function() {
            if (!this.name || this.type === "hidden" || this.disabled) return;
            this.disabled = true;
        });

        // Submete o formulário normalmente (POST, com recarregamento)
        form.submit();

    } catch (error) {
        console.error("Erro ao criptografar o formulário:", error);
    }
});

*/

document.addEventListener('DOMContentLoaded', () => {
    const publicKeyPEM = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0BxUXjrrGvXDCIplSQ7l
XfPN1PHujl9CTumnjnM58/2vCtkEaqNbVMXbqhFbqSIpbd1J2k6nn9QMyEvA2uLe
kVgQhMBhxtxFNnuMYWJAeLddas1+Vhn5jygLhdk+PxZSXi/ZKrrCqq1QwA+PSeRq
aL4StVkBNCaxXRElxWXjsPVm0JUgXAuAfzBwGeKwelSUjgoTAmTLcNOOxDL+LGYD
x7IM5PjofaiJwLj3oQpkcfsxvDZ3SMpj/Jo+V+i8OBQwCyVOAfOEvUN+O1YZlBUT
LcM7KvDLMtcQyGf//3QsjLsfqa/XEAvdAISjHO5TNAXy9MXPiEwd1cPyis7toz/d
mQIDAQAB
-----END PUBLIC KEY-----`;

    document.getElementById('meu-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        try {
            const encryptedData = await window.encryptFormFields(this, publicKeyPEM);
            if (!encryptedData) return;

            for (const [name, value] of Object.entries(encryptedData)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                this.appendChild(input);
            }

            // Desativa os campos visíveis
            this.querySelectorAll('input:not([type="hidden"])').forEach(input => input.disabled = true);

            this.submit(); // Envia o form com dados criptografados
        } catch (error) {
            console.error("Erro ao criptografar:", error);
        }
    });
});
