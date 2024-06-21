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
    <style>
        
    </style>

</head>
<body>

<?php 
//procedimento entrada
if ($entradasaida==1) { 

    $sql_entrada = "select tbentrada.id_entrada, tbentrada.id_vinculado, tbvinculados.nome, tbvinculados.nascimento, tbentrada.id_pacote, tbpacotes.descricao, tbpacotes.duracao, tbpacotes.valor, tbprevenda.datahora_efetiva, tbresponsavel.nome as nomeresponsavel, tbresponsavel.telefone1, tbresponsavel.cpf from tbentrada
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
                    <tr>
                        <td style="padding-top: 15px!important">Nome da Criança</td>
                    </tr>
                    <tr>
                        <td>Pacote - R$ valor - XXmin</td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>Início:</td>
                                    <td>10/01/2000 10:40:20</td>
                                </tr>
                                <tr>
                                    <td>Fim:</td>
                                    <td>10/01/2000 10:40:20</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
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
} elseif ($entradasaida==2) { ?>
    
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h3>Saída: 10/10/2024 00:00</h3>
            <hr>
        </div>
        <div class="col-12">
            <table>
                <thead>
                    <tr>
                        <td>Responsável: Fulano de tal</td>
                    </tr>
                    <tr>
                        <td>CPF: 123.456.789-00</td>
                    </tr>
                    <tr>
                        <td>(21) 98765-4321</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-top: 15px!important">Nome da Criança</td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td>Início:</td>
                                    <td>10/01/2000 10:40:20</td>
                                </tr>
                                <tr>
                                    <td>Saída:</td>
                                    <td>10/01/2000 10:40:20</td>
                                </tr>
                                <tr>
                                    <td>Permanência:</td>
                                    <td>10:40:20</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <p>ATENÇÃO: Será cobrado minuto adicional. Não nos responsabilizamos por objetos perdidos no local.</p>
            <p>Obrigado e volte sempre!</p>
        </div>

    </div>
</div>

<?php } ?>
</body>
</html>