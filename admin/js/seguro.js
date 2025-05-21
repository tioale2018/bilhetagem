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
