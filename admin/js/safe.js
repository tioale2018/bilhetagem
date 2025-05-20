// Função para converter PEM para ArrayBuffer
function pemToArrayBuffer(pem) {
    const b64 = pem
        .replace(/-----BEGIN PUBLIC KEY-----/, '')
        .replace(/-----END PUBLIC KEY-----/, '')
        .replace(/\s/g, '');

    const binary = atob(b64);
    const len = binary.length;
    const bytes = new Uint8Array(len);

    for (let i = 0; i < len; i++) {
        bytes[i] = binary.charCodeAt(i);
    }

    return bytes.buffer;
}

// Função que criptografa com RSA-OAEP
window.encryptRSA = async function(plainText, publicKeyPEM) {
    const encoder = new TextEncoder();
    const data = encoder.encode(plainText);

    const publicKey = await crypto.subtle.importKey(
        'spki',
        pemToArrayBuffer(publicKeyPEM),
        {
            name: 'RSA-OAEP',
            hash: 'SHA-256'
        },
        false,
        ['encrypt']
    );

    const encrypted = await crypto.subtle.encrypt(
        { name: 'RSA-OAEP' },
        publicKey,
        data
    );

    return btoa(String.fromCharCode(...new Uint8Array(encrypted)));
};

// Criptografa todos os campos de um formulário
window.encryptFormFields = async function(form, publicKeyPEM) {
    const formData = new FormData(form);
    const encryptedData = {};

    for (const [name, value] of formData.entries()) {
        if (!value) continue;

        const encrypted = await window.encryptRSA(value, publicKeyPEM);
        encryptedData[name] = encrypted;
    }

    return encryptedData;
};
