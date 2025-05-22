/*
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
    if (!input.name || input.disabled) continue;

    // Não criptografa campos ocultos; deixa eles serem enviados normalmente
    if (input.type === "hidden") {
      result[input.name] = input.value;
      continue;
    }

    const encrypted = await crypto.subtle.encrypt(
      { name: "RSA-OAEP" },
      key,
      encoder.encode(input.value)
    );

    const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
    result[input.name + "_seguro"] = encoded;

    // Remove o name do campo original (para evitar envio duplo) mas mantém o valor intacto
    input.removeAttribute("name");
  }

  return result;
};

*/
window.encryptFormFields = async function(form, publicKeyPEM) {
  if (!window.crypto || !window.crypto.subtle) {
    alert("Este navegador não suporta criptografia segura.");
    return null;
  }

  // Converte a chave PEM para ArrayBuffer
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
    if (!input.name || input.disabled) continue;

    // Campos ocultos: mantém no envio, mas não criptografa
    if (input.type === "hidden") {
      result[input.name] = input.value;
      continue;
    }

    let value = "";

    if (input.tagName === "SELECT") {
      value = input.value;
    } else if (input.type === "checkbox") {
      if (!input.checked) continue;
      value = input.value;
    } else if (input.type === "radio") {
      if (!input.checked) continue;
      value = input.value;
    } else {
      value = input.value;
    }

    if (!value) continue;

    try {
      const encrypted = await crypto.subtle.encrypt(
        { name: "RSA-OAEP" },
        key,
        encoder.encode(value)
      );

      const encoded = btoa(String.fromCharCode(...new Uint8Array(encrypted)));
      result[input.name + "_seguro"] = encoded;

      // Remove o name do campo original para evitar conflito no envio do form
      input.removeAttribute("name");
    } catch (err) {
      console.error(`Erro ao criptografar o campo "${input.name}":`, err);
      return null;
    }
  }

  return result;
};



window.encryptRSA = async function(plainText, publicKeyPEM) {
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
  const encrypted = await crypto.subtle.encrypt(
    { name: "RSA-OAEP" },
    key,
    encoder.encode(plainText)
  );

  return btoa(String.fromCharCode(...new Uint8Array(encrypted)));
};