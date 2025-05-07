window.encryptFormFields = async function(form, publicKeyPEM) {
  if (!window.crypto || !window.crypto.subtle) {
    alert("Este navegador não suporta criptografia segura.");
    return null;
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
  const result = {};

  const inputs = form.querySelectorAll("input, textarea, select");
  for (const input of inputs) {
    if (!input.name || input.type === "hidden" || input.disabled) continue;

    const encrypted = await crypto.subtle.encrypt(
      { name: "RSA-OAEP" },
      key,
      encoder.encode(input.value)
    );

    const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
    result[input.name + "_seguro"] = encoded;
  }

  return result;
};



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
  
    async function generateAESKey() {
      return crypto.subtle.generateKey(
        { name: "AES-GCM", length: 256 },
        true,
        ["encrypt"]
      );
    }
  
    function arrayBufferToBase64(buffer) {
      return btoa(String.fromCharCode(...new Uint8Array(buffer)));
    }
  
    async function encryptWithAES(key, plaintext) {
      const encoder = new TextEncoder();
      const iv = crypto.getRandomValues(new Uint8Array(12));
      const encrypted = await crypto.subtle.encrypt(
        { name: "AES-GCM", iv },
        key,
        encoder.encode(plaintext)
      );
      return { ciphertext: encrypted, iv };
    }
  
    async function encryptAESKeyWithRSA(aesKey, rsaKey) {
      const raw = await crypto.subtle.exportKey("raw", aesKey);
      return crypto.subtle.encrypt({ name: "RSA-OAEP" }, rsaKey, raw);
    }
  
    function validarNomeSobrenome(nome) {
      const partes = nome.trim().split(/\s+/);
      return partes.length >= 2 && partes.every(p => p.length >= 2);
    }
  
    // Manipulação de todos os formulários
    document.querySelectorAll("form").forEach(form => {
      form.addEventListener("submit", async event => {
        event.preventDefault();
  
        const formId = form.id;
  
        // Validações específicas para o form-busca-reserva
        if (formId === "form-busca-reserva") {
          const termos = form.querySelector('input[name="termos"]');
          if (termos && !termos.checked) {
            alert('Por favor, leia e aceite os termos de uso antes de continuar.');
            return;
          }
  
          const nomeInput = form.querySelector('input[name="nome"]');
          if (nomeInput && !validarNomeSobrenome(nomeInput.value)) {
            const erroNome = document.getElementById('erro-nome');
            if (erroNome) erroNome.style.display = 'block';
            return;
          }
        }
  
        // Início da criptografia híbrida
        const rsaKey = await importRSAPublicKey(publicKeyPEM);
        const aesKey = await generateAESKey();
        const aesKeyEncrypted = await encryptAESKeyWithRSA(aesKey, rsaKey);
        const aesKeyB64 = arrayBufferToBase64(aesKeyEncrypted);
  
        const inputs = form.querySelectorAll("input, textarea, select");
        const payload = new URLSearchParams();
        payload.append("__key_segura", aesKeyB64);
  
        for (const input of inputs) {
          if (!input.name || input.disabled || input.type === "hidden") continue;
  
          const { ciphertext, iv } = await encryptWithAES(aesKey, input.value);
          const encryptedValue = arrayBufferToBase64(ciphertext);
          const ivB64 = arrayBufferToBase64(iv);
  
          payload.append(input.name + "_seguro", `${encryptedValue}|${ivB64}`);
        }
  
        const destino = form.getAttribute("action") || window.location.href;
  
        try {
          const response = await fetch(destino, {
            method: "POST",
            body: payload,
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            }
          });
  
          const retorno = await response.text();
  
          if (formId === "form-busca-cpf") {
            const container = document.querySelector('.area-form-index');
            if (container) container.innerHTML = retorno;
          } else {
            document.open();
            document.write(retorno);
            document.close();
          }
        } catch (err) {
          console.error("Erro ao enviar o formulário:", err);
        }
      });
    });
  });
  */