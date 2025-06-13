<?php
function limparCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    return $cpf;
}

function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    // Formata o CPF no padrão 000.000.000-00
    $cpf_formatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    return $cpf_formatado;
}


function procuraResponsavel($i) {
    $id              = limparCPF($i); //$GLOBALS['id'];
    // $sql_responsavel = "select * from tbresponsavel where cpf=:cpf";
    // $sql_responsavel = "select tbresponsavel.*, tbvinculoresponsavel.descricao from tbresponsavel inner join tbvinculoresponsavel on tbresponsavel.vinculo = tbvinculoresponsavel.id where cpf=:cpf";
    $sql_responsavel = "select tbresponsavel.*, tbvinculoresponsavel.id as idvinculo, tbvinculoresponsavel.descricao as descricao_vinculo from tbresponsavel inner join tbvinculoresponsavel on tbresponsavel.vinculo = tbvinculoresponsavel.id where cpf=:cpf";
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

#função para calcular a data de nascimento com base no timestamp a ser informado


// Exemplo de uso
// $dataNascimento = "1990-07-15";
// echo "Idade: " . calcularIdade($dataNascimento) . " anos";


function convertDateToYMD($date) {
    // Verifica se a data está no formato dd/mm/yyyy usando expressão regular
    if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date, $matches)) {
        $day = $matches[1];
        $month = $matches[2];
        $year = $matches[3];
        // Retorna a data no formato yyyy-mm-dd
        return "$year-$month-$day";
    } else {
        // Retorna falso se a data não estiver no formato esperado
        return "1900-01-01";
    }
}

function convertDateToDMY($date) {
    // Tenta converter a data usando strtotime
    $timestamp = strtotime($date);
    
    // Verifica se a conversão foi bem-sucedida
    if ($timestamp) {
        // Retorna a data no formato desejado
        return date('d/m/Y', $timestamp);
    } else {
        // Retorna falso se a conversão falhar
        return "01/01/1900";
    }
}



?>