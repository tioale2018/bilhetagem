<?php
// session_start();

if ( $_SERVER['REQUEST_METHOD']!="POST" ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}

// require_once './inc/config_session.php';
require_once './inc/conexao.php';
require_once './inc/funcoes-gerais.php';
/*
if (!isset($_SESSION['user_id'])) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}

if ( verificaVar($_POST['entradasaida']) || verificaVar($_POST['entradasaida']) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
*/
$idprevenda   = $_POST['idprevenda'];
$vinculados   = (isset($_POST['vinculados'])?$_POST['vinculados']:'');
$entradasaida = $_POST['entradasaida']; //1 entrada - 2 saida
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impressão de comprovante</title>
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Dosis", sans-serif;
            font-weight: 600;
            font-style: normal;
        }
    </style>
</head>
<body>

<?php 
//procedimento entrada
if ($entradasaida==1) { 

    $sql_entrada = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome as nomecrianca, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor, tbprevenda.datahora_efetiva, tbresponsavel.nome as nomeresponsavel, tbresponsavel.telefone1, tbresponsavel.cpf from tbentrada
    inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
    inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
    inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
    inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
    where tbentrada.previnculo_status=3 and tbentrada.id_prevenda=:idprevenda";
    $pre_entrada = $connPDO->prepare($sql_entrada);
    $pre_entrada->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

    $pre_entrada->execute();
    $row_entrada = $pre_entrada->fetchAll();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>Entrada: <?= date('d/m/Y H:i:s', $row_entrada[0]['datahora_efetiva']) ?></h3>
            <hr>
        </div>
        <div class="col-12">
            <table>
                <thead>
                    <tr>
                        <td>Responsável: <?= $row_entrada[0]['nomeresponsavel'] ?></td>
                    </tr>
                    <tr>
                        <td>CPF: <?= $row_entrada[0]['cpf'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $row_entrada[0]['telefone1'] ?></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row_entrada as $key => $value) { ?>
                    <tr>
                        <td style="padding-top: 20px!important"><?= $row_entrada[$key]['nomecrianca'] ?></td>
                    </tr>
                    <tr>
                        <td>Pacote - R$ <?= $row_entrada[$key]['valor'] ?> - <?= $row_entrada[$key]['duracao'] ?>min</td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>Início:</td>
                                    <td><?= date('d/m/Y H:i:s', $row_entrada[$key]['datahora_efetiva']); ?></td>
                                </tr>
                                <tr>
                                    <td>Fim:</td>
                                    <td><?= date('d/m/Y H:i:s', $row_entrada[$key]['datahora_efetiva'] + ($row_entrada[$key]['duracao'] * 60) ); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php }  ?>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <p>ATENÇÃO: Será cobrado minuto adicional. Não nos responsabilizamos por objetos perdidos no local.</p>
            <p>Obrigado e volte sempre!</p>
        </div>

    </div>
</div>
    
<?php 
//procedimentosaida
} elseif ($entradasaida==2) { 
    
    $sql_saida = "select tbentrada.*, tbvinculados.nome as nomecrianca, tbvinculados.nascimento, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor, tbprevenda.datahora_efetiva, tbresponsavel.nome as nomeresponsavel, tbresponsavel.telefone1, tbresponsavel.cpf from tbentrada
    inner join tbvinculados on tbentrada.id_vinculado=tbvinculados.id_vinculado
    inner join tbpacotes on tbentrada.id_pacote=tbpacotes.id_pacote
    inner join tbprevenda on tbprevenda.id_prevenda=tbentrada.id_prevenda
    inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
    where tbentrada.previnculo_status=4 and tbentrada.id_prevenda=:idprevenda";

    $pre_saida = $connPDO->prepare($sql_saida);
    $pre_saida->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);

    $pre_saida->execute();
    $row_saida = $pre_saida->fetchAll();
    
    ?>
    
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>Saída: <?= $row_saida[0]['datahora_saida'] ?></h3>
            <hr>
        </div>
        <div class="col-12">
            <table>
                <thead>
                    <tr>
                        <td>Responsável: <?= $row_saida[0]['nomeresponsavel'] ?></td>
                    </tr>
                    <tr>
                        <td>CPF: <?= $row_saida[0]['cpf'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $row_saida[0]['telefone1'] ?></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($row_saida as $key => $value) { ?>
                    <tr>
                        <td style="padding-top: 15px!important"><?= $row_saida[$key]['nomecrianca'] ?></td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>Início:</td>
                                    <td><?= date('d/m/Y H:i:s', $row_saida[$key]['datahora_entra']); ?></td>
                                </tr>
                                <tr>
                                    <td>Saída:</td>
                                    <td><?= date('d/m/Y H:i:s', $row_saida[$key]['datahora_saida']); ?></td>
                                </tr>
                                <tr>
                                    <td>Permanência:</td>
                                    <td>10:40:20</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-12" style="padding-top: 20px!important">
            <p>ATENÇÃO: Será cobrado minuto adicional. Não nos responsabilizamos por objetos perdidos no local.</p>
            <p>Obrigado e volte sempre!</p>
        </div>

    </div>
</div>

<?php } ?>
</body>
</html>