<?php 
session_start();
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
include('../../admin/inc/conexao.php');
$id = $_POST['id'];

$sql_buscapacotes = "SELECT * FROM tbpacotes WHERE ativo=1 and id_evento = :idevento order by descricao";
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
        <th>Nome do pacote</th>
        <th>Valor R$</th>
        <th>Duração</th>
        <th>Tolerância</th>
        <th>Min. adicional R$</th>
        <th>Ação</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($row_buscapacotes as $key => $value) { ?>
    <tr>
        <td><?= $value['descricao'] ?></td>
        <td><?= number_format($value['valor'], 2, ",", ".") ?></td>
        <td><?= $value['duracao'] ?></td>
        <td><?= $value['tolerancia'] ?></td>
        <td><?= number_format($value['min_adicional'], 2, ",", ".") ?> </td>
        <td><a href="#" class="btn btn-sm btn-danger">Excluir</a></td>
    </tr>
    <?php } ?>
</tbody>
</table>

<?php } ?>