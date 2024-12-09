<?php
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');

$sql_buscahash = "SELECT * FROM tbevento_ativo WHERE ativo=1 and idevento = ".$_POST['id'];
$pre_buscahash = $connPDO->prepare($sql_buscahash);
$pre_buscahash->execute();

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http";


?>



<?php 
    if ($pre_buscahash->rowCount() < 1) {
        echo "<h6>Nenhuma URL cadastrada</h6>";
    } else {
        $row_buscahash = $pre_buscahash->fetchAll(PDO::FETCH_ASSOC);
    ?>

<table class="table">
    <thead>
        <tr>
            <th width="15%">Hash criado</th>
            <th width="50%">URL</th>
            <th width="10%">Ativo</th>
            <th width="20%">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php  foreach ($row_buscahash as $key => $value) { 
            $varUrl = $protocol . "://". $_SERVER['HTTP_HOST'] . "/" . $value['hash'];
            ?>
        <tr>
            <td><?= $value['hash'] ?></td>
            <td><?= $varUrl ?></td>
            <td>
                <label class="switch">
                    <input type="checkbox" class="slidercheck" checked value="1">
                    <span class="slider round "></span>
                </label>
            </td>
            <td><a href="#" class="btn btn-sm btn-success btnGeraqrcode" data-hash="<?= $varUrl ?>">Gerar QrCode</a> <a href="#" class="btn btn-sm btn-danger btnExcluihash" data-hash="<?= $value['id'] ?>">Excluir</a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

    <?php } ?>


    <script>

        $(document).ready(function() {
            $('.slidercheck').change(function() {
                // alert($());
                if($(this).is(':checked')) {
                    alert('Ativar')
                } else {
                    alert('Desativar');
                }
            });
            
        });
    </script>