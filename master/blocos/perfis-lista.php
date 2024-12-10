<?php 
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');
$id = $_POST['id'];

$sql_buscapacotes = "SELECT * FROM tbperfil_acesso WHERE ativo=1 and idevento = :idevento order by titulo";
$pre_buscapacotes = $connPDO->prepare($sql_buscapacotes);
$pre_buscapacotes->bindValue(':idevento', $id);
$pre_buscapacotes->execute();
$row_buscapacotes = $pre_buscapacotes->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($row_buscapacotes) < 1) { ?>

<h4>Nenhum pacote cadastrado</h4>

<?php } else { ?>

<table class="table table-hover">
<thead>
    <tr>
        <th>Titulo do perfil</th>
        <th>Ativo</th>
        <th>Padrão</th>
        
        <th>Ação</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($row_buscapacotes as $key => $value) { ?>
    <tr>
        <td><?= $value['titulo'] ?></td>
        <td><label class="switch">
                    <input type="checkbox" class="slidercheck" checked value="1">
                    <span class="slider round "></span>
                </label></td>
        <td>
            
            <input type="radio" <?= ($value['padrao_evento']==1?'checked':'') ?> value="<?= $value['idperfil'] ?>" name="perfilpadrao">
            
        </td>
        
        <td><a href="#" class="btn btn-sm btn-danger excluipacote" data-id="<?= $value['idperfil'] ?>">Excluir</a></td>
    </tr>
    <?php } ?>
</tbody>
</table>

<?php } ?>