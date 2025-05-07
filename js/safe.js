/*
document.addEventListener("DOMContentLoaded", () => {
    const publicKeyPEM = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0BxUXjrrGvXDCIplSQ7l
XfPN1PHujl9CTumnjnM58/2vCtkEaqNbVMXbqhFbqSIpbd1J2k6nn9QMyEvA2uLe
kVgQhMBhxtxFNnuMYWJAeLddas1+Vhn5jygLhdk+PxZSXi/ZKrrCqq1QwA+PSeRq
aL4StVkBNCaxXRElxWXjsPVm0JUgXAuAfzBwGeKwelSUjgoTAmTLcNOOxDL+LGYD
x7IM5PjofaiJwLj3oQpkcfsxvDZ3SMpj/Jo+V+i8OBQwCyVOAfOEvUN+O1YZlBUT
LcM7KvDLMtcQyGf//3QsjLsfqa/XEAvdAISjHO5TNAXy9MXPiEwd1cPyis7toz/d
mQIDAQAB
-----END PUBLIC KEY-----`;
  
    document.querySelectorAll("form").forEach(form => {
      form.addEventListener("submit", async (event) => {
        event.preventDefault();
  
        if (!window.crypto || !window.crypto.subtle) {
          alert("Este navegador não suporta criptografia segura.");
          return false;
        }
  
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
  
        // Criptografa todos os campos do formulário
        const inputs = form.querySelectorAll("input, textarea, select");
        for (const input of inputs) {
          if (!input.name || input.type === "hidden" || input.disabled) continue;
  
          const encrypted = await crypto.subtle.encrypt(
            { name: "RSA-OAEP" },
            key,
            encoder.encode(input.value)
          );
  
          const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
  
          // Cria um campo hidden com o conteúdo criptografado
          const hidden = document.createElement("input");
          hidden.type = "hidden";
          hidden.name = input.name + "_seguro";
          hidden.value = encoded;
          form.appendChild(hidden);
  
          // Desativa o campo original
          input.disabled = true;
        }
  
        form.submit();
      });
    });
  });

  */

  document.addEventListener("DOMContentLoaded", () => {
    const publicKeyPEM = `-----BEGIN PUBLIC KEY-----
  MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0BxUXjrrGvXDCIplSQ7l
  XfPN1PHujl9CTumnjnM58/2vCtkEaqNbVMXbqhFbqSIpbd1J2k6nn9QMyEvA2uLe
  kVgQhMBhxtxFNnuMYWJAeLddas1+Vhn5jygLhdk+PxZSXi/ZKrrCqq1QwA+PSeRq
  aL4StVkBNCaxXRElxWXjsPVm0JUgXAuAfzBwGeKwelSUjgoTAmTLcNOOxDL+LGYD
  x7IM5PjofaiJwLj3oQpkcfsxvDZ3SMpj/Jo+V+i8OBQwCyVOAfOEvUN+O1YZlBUT
  LcM7KvDLMtcQyGf//3QsjLsfqa/XEAvdAISjHO5TNAXy9MXPiEwd1cPyis7toz/d
  mQIDAQAB
  -----END PUBLIC KEY-----`;
  
    // Importa chave pública RSA
    async function importRSAPublicKey(pem) {
      const pemBody = pem.replace(/-----.*?-----/g, "").replace(/\s/g, "");
      const binaryDer = Uint8Array.from(atob(pemBody), c => c.charCodeAt(0));
      return crypto.subtle.importKey(
        "spki",
        binaryDer.buffer,
        { name: "RSA-OAEP", hash: "SHA-256" },
        false,
        ["encrypt"]
      );
    }
  
    // Gera chave AES aleatória
    async function generateAESKey() {
      return crypto.subtle.generateKey(
        { name: "AES-GCM", length: 256 },
        true,
        ["encrypt"]
      );
    }
  
    // Converte ArrayBuffer em base64
    function arrayBufferToBase64(buffer) {
      return btoa(String.fromCharCode(...new Uint8Array(buffer)));
    }
  
    // Codifica texto com AES
    async function encryptWithAES(key, plaintext) {
      const encoder = new TextEncoder();
      const iv = crypto.getRandomValues(new Uint8Array(12)); // 96-bit IV
      const encrypted = await crypto.subtle.encrypt(
        { name: "AES-GCM", iv },
        key,
        encoder.encode(plaintext)
      );
      return { ciphertext: encrypted, iv };
    }
  
    // Codifica chave AES com RSA
    async function encryptAESKeyWithRSA(aesKey, rsaKey) {
      const raw = await crypto.subtle.exportKey("raw", aesKey);
      return crypto.subtle.encrypt({ name: "RSA-OAEP" }, rsaKey, raw);
    }
  
    // Encripta dados do formulário
    document.querySelectorAll("form").forEach(form => {
      form.addEventListener("submit", async event => {
        event.preventDefault();
  
        const rsaKey = await importRSAPublicKey(publicKeyPEM);
        const aesKey = await generateAESKey();
  
        const aesKeyEncrypted = await encryptAESKeyWithRSA(aesKey, rsaKey);
        const aesKeyB64 = arrayBufferToBase64(aesKeyEncrypted);
  
        // Processa todos os inputs
        const inputs = form.querySelectorAll("input, textarea, select");
        for (const input of inputs) {
          if (!input.name || input.type === "hidden" || input.disabled) continue;
  
          const { ciphertext, iv } = await encryptWithAES(aesKey, input.value);
          const encryptedData = arrayBufferToBase64(ciphertext);
          const ivB64 = arrayBufferToBase64(iv);
  
          // Cria campo hidden com nome_original_seguro
          const hiddenInput = document.createElement("input");
          hiddenInput.type = "hidden";
          hiddenInput.name = input.name + "_seguro";
          hiddenInput.value = `${encryptedData}|${ivB64}`;
          form.appendChild(hiddenInput);
  
          // Desativa campo visível
          input.disabled = true;
        }
  
        // Envia chave AES criptografada via campo hidden
        const keyInput = document.createElement("input");
        keyInput.type = "hidden";
        keyInput.name = "__key_segura";
        keyInput.value = aesKeyB64;
        form.appendChild(keyInput);
  
        form.submit(); // Envio final
      });
    });
  });
  
  