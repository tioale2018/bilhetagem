<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}

if ( (!isset($_POST['p'])) || (!is_numeric($_POST['p'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}

include_once('../inc/conexao.php');
/*
$idprevenda = intval($_POST['p']);
$tempoagora = time();

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, from_unixtime(tbentrada.datahora_entra, '%H:%i:%s') as datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbresponsavel.telefone1, tbresponsavel.telefone2, from_unixtime($tempoagora, '%H:%i:%s') as horaagora, ($tempoagora-tbentrada.datahora_entra) as tempoextra
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3 and tbentrada.id_prevenda=:idprevenda
order by tbentrada.datahora_entra";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();

$row = $pre->fetchAll();

echo json_encode($row);
*/

/*
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

// Obtenha os dados do banco de dados
$idprevenda = intval($_POST['p']);
$tempoagora = time();

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbresponsavel.telefone1, tbresponsavel.telefone2, tbpacotes.min_adicional
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3 and tbentrada.id_prevenda=:idprevenda
order by tbentrada.datahora_entra";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();

$rows = $pre->fetchAll(PDO::FETCH_ASSOC);

$resultadoFinal = [];

foreach ($rows as $row) {
    $horaEntrada = $row['datahora_entra'];
    $horaSaida = $tempoagora;
    $pacote = $row['duracao'];
    $tolerancia = $row['tolerancia'];

    $calculos = calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia);

    // Combina os dados originais com os cálculos
    $resultadoFinal[] = array_merge($row, $calculos);
}

echo json_encode($resultadoFinal);
*/
?>

<?php
include_once('../inc/funcao-tempo.php');

// Obtenha os dados do banco de dados
$idprevenda = intval($_POST['p']);
$tempoagora = time();

$sql = "SELECT tbentrada.id_entrada, tbentrada.id_prevenda, tbentrada.id_vinculado, tbvinculados.nome, tbentrada.datahora_entra, tbentrada.id_pacote, tbpacotes.duracao, tbpacotes.tolerancia, tbprevenda.id_responsavel, tbresponsavel.nome as responsavel, tbresponsavel.cpf, tbresponsavel.telefone1, tbresponsavel.telefone2, tbpacotes.min_adicional, '$tempoagora' as temponow 
FROM tbentrada 
inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
inner join tbprevenda on tbentrada.id_prevenda=tbprevenda.id_prevenda
inner join tbresponsavel on tbprevenda.id_responsavel=tbresponsavel.id_responsavel
WHERE tbentrada.previnculo_status=3 and tbentrada.id_prevenda=:idprevenda
order by tbentrada.datahora_entra";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre->execute();

$rows = $pre->fetchAll(PDO::FETCH_ASSOC);

$resultadoFinal = [];

foreach ($rows as $row) {
    $horaEntrada = $row['datahora_entra'];
    $horaSaida = $tempoagora;
    $pacote = $row['duracao'];
    $tolerancia = $row['tolerancia'];

    $calculos = calcularTempoPermanencia($horaEntrada, $horaSaida, $pacote, $tolerancia);

    $resultadoFinal[] = array_merge($row, $calculos);
}

echo json_encode($resultadoFinal);

?>