function validarNomeSobrenome(campo) {
    // Remove espaços extras no início e fim do valor
    const nomeCompleto = campo.trim();
    
    // Divide o nome por espaços em branco
    const partes = nomeCompleto.split(/\s+/);

    // Verifica se há pelo menos duas partes (nome e sobrenome)
    if (partes.length < 2) {
        console.log("Erro: É necessário pelo menos um nome e um sobrenome.");
        return false;
    }

    // Verifica cada parte para garantir que tem no mínimo 2 caracteres e que contém apenas letras
    for (let parte of partes) {
        if (parte.length < 2 || /[^a-zA-ZÀ-ÖØ-öø-ÿ]/.test(parte)) {
            console.log(`Erro: A parte "${parte}" não é válida.`);
            return false;
        }
    }

    // Se todas as verificações passarem, retorna true
    return true;
}
