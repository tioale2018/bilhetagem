// Dentro do safe.js
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
