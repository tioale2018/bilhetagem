<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();

include_once('../inc/conexao.php');
include_once('../inc/funcoes.php');

$cpf  = $_POST['cpf'];

$hoje = date('Y-m-d', time());


$datafim = $_POST['datafim'];
$datainicio = $_POST['datainicio'];

function sql_busca_imprime($datainicio, $datafim, $cpf) {
    // Converter as datas para timestamps
    $timestamp_inicio = strtotime($datainicio);
    $timestamp_fim = strtotime($datafim) + 86400; // Adicionar 86400 segundos (1 dia) ao timestamp de fim

    // Construir a consulta SQL
    $sql_busca_imprime = "SELECT tbprevenda.*, tbresponsavel.nome as nome_responsavel 
                          FROM tbprevenda 
                          INNER JOIN tbresponsavel ON tbresponsavel.id_responsavel = tbprevenda.id_responsavel
                          WHERE tbprevenda.id_evento = ".$_SESSION['evento_selecionado']." 
                          AND tbprevenda.prevenda_status IN (2, 5, 6) 
                          AND tbprevenda.datahora_efetiva BETWEEN $timestamp_inicio AND $timestamp_fim
                          AND tbresponsavel.cpf = :cpf order by datahora_efetiva desc";
    
    return $sql_busca_imprime;
}

$sql_busca_imprime = sql_busca_imprime($datainicio, $datafim, $cpf);
// die($sql_busca_imprime);

$res_busca_imprime = $connPDO->prepare($sql_busca_imprime);
$res_busca_imprime->bindParam(':cpf', $cpf, PDO::PARAM_INT);
$res_busca_imprime->execute();
$row_busca_imprime = $res_busca_imprime->fetchAll();

// die("<pre>".var_dump($row_busca_imprime)."</pre>");

// echo "aqui: " . $cpf;
if ($res_busca_imprime->rowCount()>0) {  
?>
<div class="card">
    <div class="body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Ticket</th>                                                        
                    <th>Responsável</th>
                    <th>Origem Cadastro</th>
                    <th>H. Entrada</th>
                    <th>H. Saída</th>
                    <th>Imprimir</th>
                </tr>
            </thead>                                
            <tbody>
                <?php foreach ($row_busca_imprime as $key => $value) { ?>
                <tr>
                    <td><?= $value['id_prevenda'] ?></td>
                    <td><?= $value['nome_responsavel'] ?></td>
                    <td><?= ($value['origem_prevenda']==1?'Online':'Balcão'); ?></td>
                    <td><?= date('d/m/Y H:i:s', $value['datahora_efetiva']); ?></td>
                    <td><?= ($value['prevenda_status']==6?date('d/m/Y H:i:s', $value['datahora_efetiva_saida']):'<span style="color:red">Ativa</span>'); ?></td>
                    <td>
                        <a href="#" class="btn btn-primary btn-xs waves-effect modal-reimprime" title="Imprimir" data-ticket="<?= $value['id_prevenda'] ?>"><i class="material-icons">print</i></a>
                        <?php 
                        /*
                        <a href="reimprime.php?ticket=<?= $value['id_prevenda'] ?>" class="btn btn-primary btn-xs waves-effect reimprime" title="Imprimir" data-ticket="<?= $value['id_prevenda'] ?>"><i class="material-icons">print</i></a>
                        */
                        ?>
                    </td>
                </tr>
                <?php } ?>
        </table>
    </div>
</div>

<?php } else { ?>
    <div class="card">
    <div class="body">
        <h4>Nenhum resultado encontrado</h4>
    </div>
</div>
<?php }  ?>
<iframe id="printFrame" name="printFrame" style="display:none"></iframe>



<!-- <script src="./js/impressao.js"></script> -->

<script>
    $(document).ready(function() {
        $('body').on('click', '.modal-reimprime', function(e) {
            e.preventDefault();
            
            let prevenda = $(this).data('ticket');

            $('#areaModalImprime').load('./blocos/reimprime-lista-conteudo-modal.php', {p:prevenda});
            $('#ModalParticipantesImprime').modal('toggle');
            
        });

        $('body').on('click', '.imprime-termo', function(e) {
            e.preventDefault();
            let entrada = $(this).data('entrada');
            
            alert(entrada)
            
            
        })
    })
</script>


