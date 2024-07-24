<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

if ( (!isset($_POST['cpf'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}

include_once('./inc/conexao.php');
include_once('./inc/funcoes.php');

$hashevento = $_POST['hashevento'];

$cpf = limparCPF($_POST['cpf']);

$sql = "select * from tbresponsavel where ativo=1 and cpf=:cpf";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$pre->execute();

$var_cpf      = $cpf;
$var_nome     = "";
$var_email    = "";
$var_telefone = "";


if ($pre->rowCount()>0) {
    $row = $pre->fetchAll();
    $var_cpf      = $row[0]['cpf'];
    $var_nome     = $row[0]['nome'];
    $var_email    = $row[0]['email'];
    $var_telefone = $row[0]['telefone1'];
} 



//echo json_encode($row);


?>


<form class="form" method="post" action="busca-reserva.php" id="form-busca-reserva">                            
    <div class="input-group">
        <input name="cpf" type="text" class="form-control" placeholder="CPF" maxlength="14" pattern="\d*" required value="<?= $var_cpf ?>" readonly>
        <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
    </div>
    
    <div class="blocks">
        <div class="input-group">
            <input name="nome" type="text" class="form-control" placeholder="Nome" required value="<?= $var_nome ?>">
            <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
        </div>
        <div class="input-group">
            <input name="email" type="email" placeholder="E-mail" class="form-control" required value="<?= $var_email ?>" />
            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
        </div>
        <div class="input-group">
            <input name="telefone" type="text" placeholder="Telefone" class="form-control" required value="<?= $var_telefone ?>" />
            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
        </div>              
    </div>              
    <div class="checkbox">
        <input id="termos" type="checkbox" name="termos">
        <label for="termos">Li e concordo com as  <a href="" id="regras">termos de uso dos dados</a>.</label>
    </div>                            

    <div class="footer">
        <input type="hidden" name="hashevento" value="<?= $hashevento ?>">
        <button type="submit" class="btn btn-primary btn-round btn-block">Continuar</button>
    </div>
</form>

<?php if ($var_nome=="") { ?>
<script>
   $(document).ready(function() {
        $('input[name="nome"]').focus();

        

        
    });
</script>
<?php } ?>