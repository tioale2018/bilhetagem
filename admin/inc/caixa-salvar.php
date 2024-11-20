<?php

//recebe os dados de caixa-fechamento-formulario.php e salva os dados no banco de dados
// Recebe as variáveis enviadas pelo formulário usando o método POST
$ftickets       = isset($_POST['ftickets']) ? filter_input(INPUT_POST, 'ftickets', FILTER_SANITIZE_STRING) : '';
$fdinheiro      = isset($_POST['fdinheiro']) ? filter_input(INPUT_POST, 'fdinheiro', FILTER_SANITIZE_STRING) : '';
$fcartao        = isset($_POST['fcartao']) ? filter_input(INPUT_POST, 'fcartao', FILTER_SANITIZE_STRING) : '';
$fpix           = isset($_POST['fpix']) ? filter_input(INPUT_POST, 'fpix', FILTER_SANITIZE_STRING) : '';

$fval_abrecaixa = isset($_POST['fval_abrecaixa']) ? filter_input(INPUT_POST, 'fval_abrecaixa', FILTER_SANITIZE_STRING) : '';
$fval_despesas  = isset($_POST['fval_despesas']) ? filter_input(INPUT_POST, 'fval_despesas', FILTER_SANITIZE_STRING) : '';
$fval_depositos = isset($_POST['fval_depositos']) ? filter_input(INPUT_POST, 'fval_depositos', FILTER_SANITIZE_STRING) : '';
$fval_retirada  = isset($_POST['fval_retirada']) ? filter_input(INPUT_POST, 'fval_retirada', FILTER_SANITIZE_STRING) : '';
$fval_total     = isset($_POST['fval_total']) ? filter_input(INPUT_POST, 'fval_total', FILTER_SANITIZE_STRING) : '';
$fval_extra     = isset($_POST['fval_extra']) ? filter_input(INPUT_POST, 'fval_extra', FILTER_SANITIZE_STRING) : '';
$fval_final     = isset($_POST['fval_final']) ? filter_input(INPUT_POST, 'fval_final', FILTER_SANITIZE_STRING) : '';
$codcaixaform   = isset($_POST['codcaixaform']) ? filter_input(INPUT_POST, 'codcaixaform', FILTER_SANITIZE_NUMBER_INT) : '';

$lastupdate     = time();

// Converte os valores monetários para o formato numérico (se necessário)
function formatToFloat($value) {
    return floatval(str_replace(['.', ','], ['', '.'], $value));
}

// Exemplo de como usar os dados recebidos
$fdinheiro_float = formatToFloat($fdinheiro);
$fcartao_float   = formatToFloat($fcartao);
$fpix_float      = formatToFloat($fpix);

$fval_abrecaixa  = formatToFloat($fval_abrecaixa);
$fval_despesas   = formatToFloat($fval_despesas);
$fval_depositos  = formatToFloat($fval_depositos);
$fval_retirada   = formatToFloat($fval_retirada);
$fval_total      = formatToFloat($fval_total);
$fval_extra      = formatToFloat($fval_extra);
$fval_final      = formatToFloat($fval_final);

$sql = "UPDATE tbcaixa_formulario SET 
        total_tickets = :total_tickets,
        val_vendadin = :val_vendadin,
        val_vendacar = :val_vendacar,
        val_vendapix = :val_vendapix,
        val_abrecaixa = :val_abrecaixa,
        val_despesas = :val_despesas,
        val_depositos = :val_depositos,
        val_retirada = :val_retirada,
        val_total = :val_total,
        val_extra = :val_extra,
        val_final = :val_final,
        datahora_lastupdate = :datahora_lastupdate
        WHERE id = :id";        

$stmt = $connPDO->prepare($sql);

$stmt->bindParam(':total_tickets', $ftickets, PDO::PARAM_INT);
$stmt->bindParam(':val_vendadin', $fdinheiro_float, PDO::PARAM_STR);
$stmt->bindParam(':val_vendacar', $fcartao_float, PDO::PARAM_STR);
$stmt->bindParam(':val_vendapix', $fpix_float, PDO::PARAM_STR);
$stmt->bindParam(':val_abrecaixa', $fval_abrecaixa, PDO::PARAM_STR);
$stmt->bindParam(':val_despesas', $fval_despesas, PDO::PARAM_STR);
$stmt->bindParam(':val_depositos', $fval_depositos, PDO::PARAM_STR);
$stmt->bindParam(':val_retirada', $fval_retirada, PDO::PARAM_STR);
$stmt->bindParam(':val_total', $fval_total, PDO::PARAM_STR);
$stmt->bindParam(':val_extra', $fval_extra, PDO::PARAM_STR);
$stmt->bindParam(':val_final', $fval_final, PDO::PARAM_STR);
$stmt->bindParam(':datahora_lastupdate', $lastupdate, PDO::PARAM_INT);
$stmt->bindParam(':id', $codcaixaform, PDO::PARAM_INT);

$stmt->execute();

?>