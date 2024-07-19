<?php

function calcularIdade($dataNascimento) {
    // Obtém a data atual
    $dataAtual = new DateTime();
    
    // Converte a data de nascimento para um objeto DateTime
    $dataNascimento = new DateTime($dataNascimento);
    
    // Calcula a diferença entre as datas
    $idade = $dataAtual->diff($dataNascimento);
    
    // Retorna a idade em anos
    return $idade->y;
}


function calculaDuracao($minutos) {
    // Calcula as horas e minutos
    $horas = floor($minutos / 60);
    $minutosRestantes = $minutos % 60;

    // Formata a saída
    $horasFormatadas = str_pad($horas, 2, "0", STR_PAD_LEFT);
    $minutosFormatados = str_pad($minutosRestantes, 2, "0", STR_PAD_LEFT);

    return $horasFormatadas . ':' . $minutosFormatados . 'h';
}


function somarMinutos($timestamp, $minutos) {
    // Adiciona os minutos ao timestamp
    $novoTimestamp = strtotime("+$minutos minutes", $timestamp);
    
    // Formata o novo timestamp para a hora local
    $novaHoraLocal = date('H:i:s', $novoTimestamp);
    
    return $novaHoraLocal;
}


function calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia) {
    // Calcula o tempo de permanência em minutos
    $tempoPermanenciaSegundos = $horaSaida - $horaEntrada;
    $tempoPermanenciaMinutos = intdiv($tempoPermanenciaSegundos, 60);

    // Calcula a hora que deveria ter saído (entrada + pacote de minutos)
    $horaDeveriaSair = $horaEntrada + ($pacote * 60);

    // Calcula o tempo excedente (tempo de permanência - pacote - tolerância)
    $tempoPermitido = $pacote + $tolerancia;
    $tempoExcedenteMinutos = max(0, $tempoPermanenciaMinutos - $tempoPermitido);

    // Formata as horas de entrada e saída no formato 24h
    $horaEntradaFormatada = date('H:i', $horaEntrada);
    $horaSaidaFormatada = date('H:i', $horaSaida);
    $horaDeveriaSairFormatada = date('H:i', $horaDeveriaSair);

    // Formata o tempo de permanência e o tempo excedente no formato hh:mm
    $tempoPermanenciaFormatado = sprintf('%02d:%02d', intdiv($tempoPermanenciaMinutos, 60), $tempoPermanenciaMinutos % 60);
    $tempoExcedenteFormatado = sprintf('%02d:%02d', intdiv($tempoExcedenteMinutos, 60), $tempoExcedenteMinutos % 60);

    // Retorna os resultados em um array
    return [
        'horaEntrada' => $horaEntradaFormatada,
        'horaSaida' => $horaSaidaFormatada,
        'horaDeveriaSair' => $horaDeveriaSairFormatada,
        'tempoPermanencia' => $tempoPermanenciaFormatado,
        'tempoExcedente' => $tempoExcedenteFormatado,
        'tempoPermanenciaMinutos' => $tempoPermanenciaMinutos, // Adiciona o tempo de permanência em minutos como inteiro
        'tempoExcedenteMinutos' => $tempoExcedenteMinutos // Adiciona o tempo excedente em minutos como inteiro
    ];
}



function convertTimestampToBRT($timestamp) {
    // Criar um objeto DateTime a partir do timestamp
    $dt = new DateTime("@$timestamp");
    // Ajustar para o fuso horário de Brasília
    $dt->setTimezone(new DateTimeZone('America/Sao_Paulo'));
    // Retornar a hora formatada no formato HH:MM:SS
    return $dt->format('H:i:s');
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

function verificaVar($variable) {
    // Verifica se a variável está definida e se não é nula
    if (isset($variable)) {
        // Verifica se a variável é numérica
        if (is_numeric($variable)) {
            return true;
        }
    }
    return false;
}

function obterNomeESobrenome($nomeCompleto) {
    // Divide o nome completo em partes usando o espaço como delimitador
    $partes = explode(' ', $nomeCompleto);
    
    // Se o nome completo tiver apenas uma palavra, retorna ela mesma
    if (count($partes) == 1) {
        return $nomeCompleto;
    }

    // Obtem a primeira e a última parte do nome
    $primeiroNome = $partes[0];
    $ultimoSobrenome = $partes[count($partes) - 1];
    
    // Retorna o primeiro nome e o último sobrenome
    return $primeiroNome . ' ' . $ultimoSobrenome;
}
?>