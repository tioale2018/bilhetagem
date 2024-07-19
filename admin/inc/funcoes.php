<?php
function limparCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    return $cpf;
}

function procuraResponsavel($i) {
    // $id              = limparCPF($i); //$GLOBALS['id'];
    $sql_responsavel = "select * from tbresponsavel where id_responsavel=:cpf";
    $pre_responsavel = $GLOBALS['connPDO']->prepare($sql_responsavel);
    $pre_responsavel->bindParam(':cpf', $i, PDO::PARAM_INT);
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

function isValidDate($date) {
    // Tentar criar um objeto DateTime a partir da string fornecida
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    // Verificar se a string corresponde ao formato especificado e se é uma data válida
    return $dateTime && $dateTime->format('Y-m-d') === $date;
}


function generateSqlQuery($date) {
    // Criar um objeto DateTime a partir da data fornecida
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    if ($dateTime === false) {
        throw new Exception('Data inválida. Use o formato YYYY-MM-DD.');
    }

    // Obter o timestamp do início do dia
    $startTimestamp = $dateTime->setTime(0, 0)->getTimestamp();

    // Obter o timestamp do final do dia
    $endTimestamp = $dateTime->setTime(23, 59, 59)->getTimestamp();

    // Criar a query SQL
    $sql = "SELECT * FROM tbfinanceiro WHERE ativo=1 AND hora_pgto BETWEEN {$startTimestamp} AND {$endTimestamp}";

    return $sql;
}

//colocar isso numa tabela no banco no futuro
$tpcobranca[1] = "Entrada";
$tpcobranca[2] = "Saida";
$tpcobranca[3] = "Extra";
$tpcobranca[4] = "Saída + Extra";

$formapgto[1] = "Cartão de crédito";
$formapgto[2] = "Cartão de debito";
$formapgto[3] = "Dinheiro";
$formapgto[4] = "Pix";
$formapgto[5] = "Misto";


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
