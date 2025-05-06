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
  