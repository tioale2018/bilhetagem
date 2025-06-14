<?php //include_once('./inc/head.php') ?>
<?php
// Inclui o arquivo de configuração de sessão
require_once './inc/config_session.php';
require_once './inc/functions.php';

verificarSessao();

?>
<!doctype html>
<html class="no-js " lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">

    <title>Sistema de Bilhetagem</title>
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
$idprevenda  = htmlspecialchars($_GET['t'], ENT_QUOTES, 'UTF-8'); //$_GET['t'];
/*
$sql_buscaevento = "select * from tbevento where id_evento=".$_SESSION['evento_selecionado'];
$pre_buscaevento = $connPDO->prepare($sql_buscaevento);
$pre_buscaevento->execute();
$row_buscaevento = $pre_buscaevento->fetch(PDO::FETCH_ASSOC);
*/

/*

$sql_participante = "SELECT tbvinculados.nome as participantenome, tbvinculados.nascimento, tbresponsavel.nome as responsavelnome, tbresponsavel.cpf, tbresponsavel.telefone1, tbentrada.datahora_autoriza FROM tbentrada
inner join tbvinculados on tbvinculados.id_vinculado=tbentrada.id_vinculado
inner join tbresponsavel on tbresponsavel.id_responsavel=tbvinculados.id_responsavel
where tbentrada.id_entrada=:entrada";
$pre_participante = $connPDO->prepare($sql_participante);
$pre_participante->bindParam(':entrada', $entrada, PDO::PARAM_INT);
$pre_participante->execute();
$row_participante = $pre_participante->fetch(PDO::FETCH_ASSOC);

*/
// $sql_deviceinfo = "SELECT * FROM device_info where id_entrada=:entrada";
// $pre_deviceinfo = $connPDO->prepare($sql_deviceinfo);
// $pre_deviceinfo->bindParam(':entrada', $entrada, PDO::PARAM_INT);
// $pre_deviceinfo->execute();

// if ($pre_deviceinfo->rowCount() < 1) {

$sql_buscaPrevenda ="SELECT * FROM tbprevenda WHERE id_prevenda =:idprevenda";
$pre_prevenda = $connPDO->prepare($sql_buscaPrevenda);
$pre_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre_prevenda->execute();
$row_prevenda = $pre_prevenda->fetch(PDO::FETCH_ASSOC);

// die(var_dump($row_prevenda));

    $ievento = $row_prevenda['id_evento'];
    $sql_busca_termo = "SELECT * from tbtermo where ativo=1 and idevento=:evento";
    $pre_busca_termo = $connPDO->prepare($sql_busca_termo);
    $pre_busca_termo->bindParam(':evento', $ievento, PDO::PARAM_INT);
// } else {
//     $row_deviceinfo = $pre_deviceinfo->fetch(PDO::FETCH_ASSOC);
//     $iativo = $row_deviceinfo['termoativo'];
//     $sql_busca_termo = "SELECT * from tbtermo where idtermo=:termo";
//     $pre_busca_termo = $connPDO->prepare($sql_busca_termo);
//     $pre_busca_termo->bindParam(':termo', $iativo, PDO::PARAM_INT);   
// }

// $pre_busca_termo = $connPDO->prepare($sql_busca_termo);
$pre_busca_termo->execute();
$row_busca_termo = $pre_busca_termo->fetch(PDO::FETCH_ASSOC);


$variables = [
    // 'responsavelnome' => $row_participante['responsavelnome'],
    // 'responsavelcpf' => $row_participante['cpf'],
    // 'responsaveltel1' => $row_participante['telefone1'],
    // 'participantenome' => $row_participante['participantenome'],
    // 'participantenascimento' => date('d/m/Y', strtotime($row_participante['nascimento'])), 
    // 'participanteidade' => calculateAge($row_participante['nascimento']),
    // 'datahoje' => $row_participante['datahora_autoriza']==''?'':formatDate($row_participante['datahora_autoriza']),
    'datahoje' => formatDate(time()),
    'cidadetermo' => ($row_busca_termo['cidadetermo']==''?'Rio de Janeiro':$row_busca_termo['cidadetermo']),
    'empresatermo' => $row_busca_termo['empresa'],
    'cnpjtermo' => $row_busca_termo['cnpj']
];




$sql_buscaReponsavel = "SELECT tbprevenda.id_prevenda, tbprevenda.data_acesso, tbprevenda.datahora_solicita, tbresponsavel.nome as nomeresponsavel, tbresponsavel.cpf as cpfresponsavel, tbresponsavel.email as emailresponsavel, tbresponsavel.telefone1 as telefoneresponsavel, tbresponsavel.endereco, tbresponsavel.bairro, tbresponsavel.cidade, tbsecundario.cpf as cpfsecundario, tbsecundario.nome as nomesecundario, tbsecundario.telefone as telefonesecundario
FROM tbprevenda
inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
inner join tbsecundario on tbsecundario.idprevenda=tbprevenda.id_prevenda
WHERE tbsecundario.ativo=1 and tbprevenda.id_prevenda = :idprevenda";
$pre_buscaReponsavel = $connPDO->prepare($sql_buscaReponsavel);
$pre_buscaReponsavel->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
$pre_buscaReponsavel->execute();
$row_buscaReponsavel = $pre_buscaReponsavel->fetch(PDO::FETCH_ASSOC);
// die(var_dump($row_buscaReponsavel));"


// include_once('../inc/variaveis-termo.php');
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<style>
    * {
        /* line-height: 0.8em; */
        font-size: 12px;
        font-family: "Roboto", sans-serif;
        font-optical-sizing: auto;
        /* font-weight: normal; */
        font-style: normal;
        
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
    }
    th, td, tr {
        text-align: left;
        vertical-align: top;
    }
   p,h4 {
       margin: 0;
       padding: 0;
   }
   ul {
         margin-top: 0;
         padding-top: 0;
            margin-bottom: 0;
            padding-bottom: 0;
         /* list-style-type: none; */
   }
body {
    margin: 0;
    padding: 0 10px;
}
</style>
</head>
<body>
<?= "<h2>Termo de autorização</h2>"; ?>
<table style="width: 100%">
    <tr>
        <th>Nome do responsável principal</th>
        <td><?= $row_buscaReponsavel['nomeresponsavel'] ?></td>
    </tr>
    <tr>
        <th>CPF do responsável principal</th>
        <td><?= $row_buscaReponsavel['cpfresponsavel'] ?></td>
    </tr>
    <tr>
        <th>Telefone do responsável principal</th>
        <td><?= $row_buscaReponsavel['telefoneresponsavel'] ?></td>
    </tr>
    <tr>
        <th>Email do responsável principal</th>
        <td><?= $row_buscaReponsavel['emailresponsavel'] ?></td>
    </tr>
    <tr>
        <th>Endereço do responsável principal</th>
        <td><?= $row_buscaReponsavel['enderecoresponsavel'] ?></td>
    </tr>
    <tr>
        <th>Nome do responsável secundário</th>
        <td><?= $row_buscaReponsavel['nomeresecundario'] ?></td>
    </tr>
    <tr>
        <th>CPF do responsável secundário</th>
        <td><?= $row_buscaReponsavel['cpfsecundario'] ?></td>
    </tr>
    <tr>
        <th>Telefone do responsável secundário</th>
        <td><?= $row_buscaReponsavel['telefonesecundario'] ?></td>
    </tr>
</table>
<p>Participantes:</p>
<table style="width: 100%">
    <tr>
        <th>Nome do participante</th>
        <td>{{nomeparticipante}}</td>
    </tr>
    <tr>
        <th>Idade do participante</th>
        <td>{{idade}} Anos</td>
    </tr>
    <tr>
        <th>Pacote</th>
        <td>{{pacote}}</td>
    </tr>

</table>
<?= replaceVariables($row_busca_termo['textotermo'], $variables); ?>

<div style="border-top: 1px solid #000; margin: 30px auto; width: 80%; ">
    <p style="text-align: center;">Assinatura do responsável principal</p>

</div>
<div >
    <?php /*
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

 */ ?>
</div>

</body>
</html>
<!-- <p>Responsável: {{responsavelnome}}<br>
CPF: {{responsavelcpf}} - Tel.: {{responsaveltel1}}</p>
<p>Participante: {{participantenome}}<br>
Nascimento: {{participantenascimento}} - Idade: {{participanteidade}} anos</p> -->
