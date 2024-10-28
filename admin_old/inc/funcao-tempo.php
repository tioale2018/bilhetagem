<?php

// Função ajustada para calcular o tempo de permanência e tempo excedente
/*
function calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia) {
    // Calcula o tempo de permanência em segundos
    $tempoPermanenciaSegundos = $horaSaida - $horaEntrada;
    // Converte o tempo de permanência para minutos
    $tempoPermanenciaMinutos = intdiv($tempoPermanenciaSegundos, 60);
    
    // Calcula a hora que deveria ter saído (entrada + pacote de minutos)
    $horaDeveriaSair = $horaEntrada + ($pacote * 60);

    // Calcula o tempo excedente (tempo de permanência - pacote - tolerância)
    $tempoPermitido = $pacote + $tolerancia;
    $tempoExcedenteSegundos = max(0, $tempoPermanenciaSegundos - ($tempoPermitido * 60));
    $tempoExcedenteMinutos = ceil($tempoExcedenteSegundos / 60);

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
*/
/*
function calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia) {
    // Calcula o tempo de permanência em segundos
    $tempoPermanenciaSegundos = $horaSaida - $horaEntrada;
    // Converte o tempo de permanência para minutos completos
    $tempoPermanenciaMinutos = intdiv($tempoPermanenciaSegundos, 60);
    
    // Calcula a hora que deveria ter saído (entrada + pacote de minutos)
    $horaDeveriaSair = $horaEntrada + ($pacote * 60);

    // Calcula o tempo excedente (tempo de permanência - pacote - tolerância)
    $tempoPermitido = $pacote + $tolerancia;
    $tempoExcedenteSegundos = max(0, $tempoPermanenciaSegundos - ($tempoPermitido * 60));
    // Converte o tempo excedente para minutos completos
    $tempoExcedenteMinutos = intdiv($tempoExcedenteSegundos, 60);

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

*/
function calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia) {
    // Calcula o tempo de permanência em segundos
    $tempoPermanenciaSegundos = $horaSaida - $horaEntrada;
    
    // Converte o tempo de permanência para minutos completos
    $tempoPermanenciaMinutos = floor($tempoPermanenciaSegundos / 60);
    
    // Calcula a hora que deveria ter saído (entrada + pacote de minutos)
    $horaDeveriaSair = $horaEntrada + ($pacote * 60);

    // Calcula o tempo excedente (tempo de permanência - pacote - tolerância)
    $tempoPermitido = $pacote + $tolerancia;
    $tempoExcedenteSegundos = max(0, $tempoPermanenciaSegundos - ($tempoPermitido * 60));
    
    // Converte o tempo excedente para minutos completos
    $tempoExcedenteMinutos = floor($tempoExcedenteSegundos / 60);

    // Formata as horas de entrada e saída no formato 24h
    $horaEntradaFormatada = date('H:i', $horaEntrada);
    $horaSaidaFormatada = date('H:i', $horaSaida);
    $horaDeveriaSairFormatada = date('H:i', $horaDeveriaSair);

    // Formata o tempo de permanência e o tempo excedente no formato hh:mm
    $tempoPermanenciaFormatado = sprintf('%02d:%02d', floor($tempoPermanenciaMinutos / 60), $tempoPermanenciaMinutos % 60);
    $tempoExcedenteFormatado = sprintf('%02d:%02d', floor($tempoExcedenteMinutos / 60), $tempoExcedenteMinutos % 60);

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

?>