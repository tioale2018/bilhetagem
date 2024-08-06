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

$sql_busca_imprime = "SELECT tbprevenda.*, tbresponsavel.nome as nome_responsavel FROM tbprevenda 
inner join tbresponsavel on tbresponsavel.id_responsavel=tbprevenda.id_responsavel
WHERE tbprevenda.id_evento=".$_SESSION['evento_selecionado']." and tbprevenda.prevenda_status in (2,5,6) and data_acesso='$hoje' and tbresponsavel.cpf=:cpf";
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
                    <td><?= date('d/m/Y H:i:s', $value['datahora_efetiva']); ?></td>
                    <td><?= ($value['prevenda_status']==6?date('d/m/Y H:i:s', $value['datahora_efetiva_saida']):'<span style="color:red">Ativa</span>'); ?></td>
                    <td>
                        <a href="reimprime.php?ticket=<?= $value['id_prevenda'] ?>" class="btn btn-primary btn-xs waves-effect reimprime" title="Imprimir" data-tipo="<?= ($value['prevenda_status']==6?'2':'1'); ?>" data-ticket="<?= $value['id_prevenda'] ?>"><i class="material-icons">print</i></a>
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
        $('body').on('click', '.reimprime', function(e) {
            e.preventDefault();
            // printAnotherDocument('comprovante.php', '#formImpressao');
            
            var prevenda = $(this).data('ticket');
            var tipo = $(this).data('tipo');

            $.ajax({
                method: "POST",
                url: './blocos/reimprime-comprovante.php',
                data: {idprevenda: prevenda, entradasaida: tipo},
                success: function(data) {
                    var printFrame = document.getElementById('printFrame');
                    var printFrameWindow = printFrame.contentWindow || printFrame;

                    printFrame.contentDocument.open();
                    printFrame.contentDocument.write(data);
                    printFrame.contentDocument.close();

                    /*
                    $(printFrameWindow).on('afterprint', function() {
                        window.location.href = './controle.php';
                    });
                    */

                    printFrameWindow.focus();
                    printFrameWindow.print();
                },
                error: function(data) {
                    alert('Falha ao carregar o documento para impressão.' + data);
                }
            });
            
        })
    })
    </script>

