<?php include_once('./inc/head.php') ?>
<?php
@session_start();

if ( (!isset($_GET['t'])) || (!is_numeric($_GET['t']) ) ){
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}


// $_GET['t']  = 60403;

function replaceVariables($text, $variables) {
    foreach ($variables as $key => $value) {
        // Cria o padrão da variável, por exemplo, {{var1}}
        $pattern = '{{' . $key . '}}';
        // Substitui todas as ocorrências do padrão no texto pelo valor correspondente
        $text = str_replace($pattern, $value, $text);
    }
    return $text;
}

function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

function formatDate($timestamp) {
    // Array com os nomes dos meses em português
    $months = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];
    
    // Extrai o dia, mês e ano do timestamp
    $day = date('d', $timestamp);
    $month = date('n', $timestamp);
    $year = date('Y', $timestamp);

    // Monta a data formatada
    $formattedDate = sprintf('%d de %s de %d', $day, $months[$month], $year);
    
    // Retorna a data formatada
    return $formattedDate;
}


include_once('./inc/conexao.php');
include_once('./inc/funcoes-gerais.php');
include_once('./inc/funcoes.php');
$entrada  = $_GET['t'];
/*
$sql_buscaevento = "select * from tbevento where id_evento=".$_SESSION['evento_selecionado'];
$pre_buscaevento = $connPDO->prepare($sql_buscaevento);
$pre_buscaevento->execute();
$row_buscaevento = $pre_buscaevento->fetch(PDO::FETCH_ASSOC);
*/

$sql_participante = "SELECT tbvinculados.nome as participantenome, tbvinculados.nascimento, tbresponsavel.nome as responsavelnome, tbresponsavel.cpf, tbresponsavel.telefone1, tbentrada.datahora_autoriza FROM tbentrada
inner join tbvinculados on tbvinculados.id_vinculado=tbentrada.id_vinculado
inner join tbresponsavel on tbresponsavel.id_responsavel=tbvinculados.id_responsavel
where tbentrada.id_entrada=:entrada";
$pre_participante = $connPDO->prepare($sql_participante);
$pre_participante->bindParam(':entrada', $entrada, PDO::PARAM_INT);
$pre_participante->execute();
$row_participante = $pre_participante->fetch(PDO::FETCH_ASSOC);


$sql_deviceinfo = "SELECT * FROM device_info where id_entrada=:entrada";
$pre_deviceinfo = $connPDO->prepare($sql_deviceinfo);
$pre_deviceinfo->bindParam(':entrada', $entrada, PDO::PARAM_INT);
$pre_deviceinfo->execute();

if ($pre_deviceinfo->rowCount() < 1) {
    $sql_busca_termo = "select * from tbtermo where ativo=1 and idevento=:evento";
    $pre_busca_termo = $connPDO->prepare($sql_busca_termo);
    $pre_busca_termo->bindParam(':evento', $_SESSION['evento_selecionado'], PDO::PARAM_INT);
} else {
    $row_deviceinfo = $pre_deviceinfo->fetch(PDO::FETCH_ASSOC);
    $sql_busca_termo = "select * from tbtermo where idtermo=:termo";
    $pre_busca_termo = $connPDO->prepare($sql_busca_termo);
    $pre_busca_termo->bindParam(':termo', $row_deviceinfo['termoativo'], PDO::PARAM_INT);
}

$pre_busca_termo = $connPDO->prepare($sql_busca_termo);
$pre_busca_termo->execute();
$row_busca_termo = $pre_busca_termo->fetch(PDO::FETCH_ASSOC);

/*
$variables = [
    'responsavelnome' => $row_participante['responsavelnome'],
    'responsavelcpf' => $row_participante['cpf'],
    'responsaveltel1' => $row_participante['telefone1'],
    'participantenome' => $row_participante['participantenome'],
    'participantenascimento' => date('d/m/Y', strtotime($row_participante['nascimento'])), 
    'participanteidade' => calculateAge($row_participante['nascimento']),
    'datahoje' => $row_participante['datahora_autoriza']==''?'':formatDate($row_participante['datahora_autoriza']),
    'cidadetermo' => ($row_busca_termo['cidadetermo']==''?'Rio de Janeiro':$row_busca_termo['cidadetermo']),
    'empresa' => $row_busca_termo['empresa'],
    'cnpj' => $row_busca_termo['cnpj']
];
*/
include_once('../inc/variaveis-termo.php');
?>

<?= "<h2>Termo de autorização</h2>"; ?>
<?= replaceVariables($row_busca_termo['textotermo'], $variables); ?>

<div >
<table style="font-size: 0.8em">
    <tr>
        <td>Data hora da autorização: </td>
        <td><?= $row_participante['datahora_autoriza']==''?'Unknown':date('d/m/Y H:i:s', $row_participante['datahora_autoriza']) ?></td>
    </tr>
    <?php if (isset($row_deviceinfo)) { ?>
    <tr>
        <td>Ip address:</td>
        <td><?= $row_deviceinfo['ip_address'] ?></td>
    </tr>
    <tr>
        <td>User agent:</td>
        <td><?= $row_deviceinfo['user_agent'] ?></td>
    </tr>
    <tr>
        <td>Screen resolution:</td>
        <td><?= $row_deviceinfo['screen_resolution'] ?></td>
    </tr>
    <tr>
        <td>Device type:</td>
        <td><?= $row_deviceinfo['device_type'] ?></td>
    </tr>
    <tr>
        <td>Browser language:</td>
        <td><?= $row_deviceinfo['browser_language'] ?></td>
    </tr>
    <tr>
        <td>Server language:</td>
        <td><?= $row_deviceinfo['server_language'] ?></td>
    </tr>
    <tr>
        <td>Operating system:</td>
        <td><?= $row_deviceinfo['operating_system'] ?></td>
    </tr>
    <tr>
        <td>Time zone:</td>
        <td><?= $row_deviceinfo['time_zone'] ?></td>
    </tr>
    <tr>
        <td>Connection type</td:>    
        <td><?= $row_deviceinfo['connection_type'] ?></td>
    </tr>    

    <?php } ?>   
</table>
</div>


<!-- <p>Responsável: {{responsavelnome}}<br>
CPF: {{responsavelcpf}} - Tel.: {{responsaveltel1}}</p>
<p>Participante: {{participantenome}}<br>
Nascimento: {{participantenascimento}} - Idade: {{participanteidade}} anos</p> -->
