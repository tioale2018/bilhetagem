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

        // Cria um formulário oculto com os dados criptografados
        const newForm = document.createElement('form');
        newForm.method = 'POST';
        newForm.action = 'index.php';

        for (const key in encryptedData) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = encryptedData[key];
            newForm.appendChild(input);
        }

        document.body.appendChild(newForm);
        newForm.submit(); // envia os dados criptografados e recarrega a página

    } catch (error) {
        console.error("Erro ao criptografar:", error);
    }
});

