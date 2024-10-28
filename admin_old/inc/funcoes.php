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

//colocar isso numa tabela no banco no futuro
$tpcobranca[1] = "Entrada";
$tpcobranca[2] = "Saida";
$tpcobranca[3] = "Extra";
$tpcobranca[4] = "Saída + Extra";

$formapgto[0] = "N/A";
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


function formatMinutesToHours($totalMinutes) {
    // Calcula o número de horas
    $hours = floor($totalMinutes / 60);
    
    // Calcula o número de minutos restantes
    $minutes = $totalMinutes % 60;
    
    // Formata as horas e minutos para garantir dois dígitos
    $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    
    // Retorna o resultado no formato HH:MM
    return "$formattedHours:$formattedMinutes";
}

function obterIP() {
    // Tenta obter o IP real através de cabeçalhos HTTP
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
  
    // Valida o formato do IP
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
      return $ip;
    } else {
      return "IP inválido";
    }
  }

?>
