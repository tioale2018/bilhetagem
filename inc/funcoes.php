<?php
function limparCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    return $cpf;
}


function procuraResponsavel($i) {
    $id              = limparCPF($i); //$GLOBALS['id'];
    $sql_responsavel = "select * from tbresponsavel where cpf=:cpf";
    $pre_responsavel = $GLOBALS['connPDO']->prepare($sql_responsavel);
    $pre_responsavel->bindParam(':cpf', $id, PDO::PARAM_INT);
    $pre_responsavel->execute();

    if ($pre_responsavel->rowCount()>0) {
        $dados_responsavel = $pre_responsavel->fetchAll();
        return $dados_responsavel;
    } else {
        return false;
    }
}


function searchInMultidimensionalArray(array $array, $key, $value) {
    // Usa array_column para obter uma coluna de valores
    $column = array_column($array, $key);
    // Busca o índice do valor procurado na coluna
    $index = array_search($value, $column);
    // Se o valor for encontrado, retorna o subarray correspondente
    if ($index !== false) {
        return $array[$index];
    }
    // Se não for encontrado, retorna null
    return null;
}


function calcularIdade($dataNascimento) {
    // Cria um objeto DateTime a partir da data de nascimento
    $dataNascimentoObj = new DateTime($dataNascimento);
    // Obtém a data atual
    $dataAtual = new DateTime();
    // Calcula a diferença entre a data atual e a data de nascimento
    $idade = $dataAtual->diff($dataNascimentoObj);
    // Retorna a idade em anos
    return $idade->y;
}

// Exemplo de uso
// $dataNascimento = "1990-07-15";
// echo "Idade: " . calcularIdade($dataNascimento) . " anos";

?>