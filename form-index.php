<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}

if ( (!isset($_POST['cpf'])) ) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
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

?>


<form class="form" method="post" action="busca-reserva.php" id="form-busca-reserva">                            
    <div class="input-group">
        <input name="cpf" type="text" class="form-control" placeholder="CPF" maxlength="14" pattern="\d*" required value="<?= $var_cpf ?>" readonly>
        <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
    </div>
    
    <div class="blocks">
        <div class="input-group">
            <input name="nome" type="text" class="form-control" placeholder="Nome" required value="<?= $var_nome ?>">
            <span class="input-group-addon"><i class="material-icons">spellcheck</i></span>
        </div>
        <div id="erro-nome" style="color:red"><small>Informe o nome com ao menos um sobrenome</small></div>
        
        <div class="input-group">
            <input name="telefone" type="text" placeholder="Telefone" class="form-control" required value="<?= $var_telefone ?>" />
            <span class="input-group-addon"><i class="material-icons">phone</i></span>
        </div>   
        <div class="input-group">
            <input name="email" type="email" placeholder="E-mail" class="form-control" value="<?= $var_email ?>" />
            <span class="input-group-addon"><i class="material-icons">email</i></span>
        </div>           
    </div>
   
    <div class="checkbox">
        <input id="comunica" type="checkbox" name="comunica" value="1" checked>
        <label for="comunica">Autorizo o uso dos meus dados de acordo com a <a href="#" id="regrascomunica">política de comunicação comercial</a> da empresa.</label>
    </div>  
              
    <div class="checkbox">
        <input id="termos" type="checkbox" name="termos" value="1" checked required>
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
        $('#erro-nome').hide();
        $('input[name="nome"]').focus();

        $('input[name=telefone]').mask('(00) 0000-00000', {
            onKeyPress: function(val, e, field, options) {
                var mask = (val.length > 14) ? '(00) 00000-0000' : '(00) 0000-00000';
                $('input[name=telefone]').mask(mask, options);
            }
        });
        
    });
</script>
<?php } ?>