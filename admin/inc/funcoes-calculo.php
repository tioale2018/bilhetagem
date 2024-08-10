<?php
/*
function calculaExcedente($horaEntrada, $horaSaida, $duracao, $tolerancia) {
    // Converte as strings de timestamp para inteiros
    $horaEntrada = intval($horaEntrada);
    $horaSaida = intval($horaSaida);
    
    // Calcula o tempo de permanência em segundos
    $tempoPermanenciaSegundos = $horaSaida - $horaEntrada;
    // Converte o tempo de permanência para minutos
    $tempoPermanenciaMinutos = $tempoPermanenciaSegundos / 60;

    // Calcula o tempo permitido (duração + tolerância) em minutos
    $tempoPermitido = $duracao + $tolerancia;

    // Calcula o tempo excedente em minutos
    $tempoExcedenteMinutos = max(0, $tempoPermanenciaMinutos - $tempoPermitido);

    // Arredonda para cima o tempo excedente em minutos
    $tempoExcedenteMinutos = ceil($tempoExcedenteMinutos);

    // Retorna o tempo excedente em minutos como inteiro
    return (int)$tempoExcedenteMinutos;
}
*/

/*
function calcularPermanenciaEmMinutos($entradaTimestamp, $saidaTimestamp) {
    // Calcula a diferença em segundos entre os dois timestamps
    $diferencaEmSegundos = $saidaTimestamp - $entradaTimestamp;
    
    // Converte a diferença de segundos para minutos
    $diferencaEmMinutos = $diferencaEmSegundos / 60;
    
    // Retorna o resultado arredondado para o inteiro mais próximo
    return round($diferencaEmMinutos);
}

*/
function calcularPermanenciaEmMinutos($entradaTimestamp, $saidaTimestamp) {
    // Calcula a diferença em segundos entre os dois timestamps
    $diferencaEmSegundos = $saidaTimestamp - $entradaTimestamp;
    
    // Converte a diferença de segundos para minutos completos
    $diferencaEmMinutos = $diferencaEmSegundos / 60;
    
    // Retorna o valor inteiro, descartando os segundos restantes
    return floor($diferencaEmMinutos);
}



// Exemplo de uso
/*
$horaEntrada = strtotime('2024-05-14 08:00:00'); // Exemplo de timestamp de entrada
$horaSaida = strtotime('2024-05-14 10:30:00');   // Exemplo de timestamp de saída
$duracao = 120;  // Duração do pacote em minutos
$tolerancia = 15; // Tolerância em minutos

$tempoExcedente = calculaExcedente($horaEntrada, $horaSaida, $duracao, $tolerancia);
echo "Tempo excedente: " . $tempoExcedente . " minutos";
*/


// Exemplo de uso
// $horaEntrada = '1684050000'; // Exemplo de timestamp de entrada como string
// $horaSaida = '1684059000';   // Exemplo de timestamp de saída como string
// $duracao = 120;  // Duração do pacote em minutos
// $tolerancia = 15; // Tolerância em minutos

// $tempoExcedente = calculaExcedente($horaEntrada, $horaSaida, $duracao, $tolerancia);
// echo "Tempo excedente: " . $tempoExcedente . " minutos";

// die('----');


function calcularExcedente($duracao, $tolerancia, $permanencia) {
    // Calcula o tempo máximo permitido (duração + tolerância)
    $tempoMaximo = $duracao + $tolerancia;
    
    // Verifica se a permanência excede o tempo máximo permitido
    if ($permanencia > $tempoMaximo) {
        // Calcula e retorna a diferença excedente
        return $permanencia - $duracao;
    } else {
        // Se não houver excedente, retorna zero
        return 0;
    }
}

?>