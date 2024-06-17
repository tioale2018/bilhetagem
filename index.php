<!doctype html>
<html class="no-js " lang="pt-br">
<head>
<?php include('./inc/head.php') ?>
<?php include('./inc/conexao.php') ?>
<?php 
function buscarPorHash($array, $hash) {
    // Percorre o array principal
    foreach ($array as $item) {
        // Verifica se o valor do hash corresponde ao hash informado
        if (isset($item['hash']) && $item['hash'] === $hash) {
            // Retorna o item correspondente
            return $item;
        }
    }
    // Se nenhum item for encontrado, retorna null ou uma mensagem de erro
    return null;
}

//$sql = "select hash from tbativa where ativo=1";
$sql = "select tbativa.hash, tbativa.idevento, tbevento.titulo, tbevento.local, tbevento.modo_pgto
from tbativa 
inner join tbevento on tbativa.idevento=tbevento.id_evento
where tbativa.ativo=1 and tbevento.status=2";
$pre = $connPDO->prepare($sql);
$pre->execute();
$row = $pre->fetchAll();

foreach ($row as $key => $value) {
    $lista_hash[] = $row[$key][0];
}

if ( !isset($_GET['i']) || !in_array($_GET['i'],$lista_hash)) {
    header("Location: 404.php");
}

$dadosEvento = buscarPorHash($row, $_GET['i']);

?>
<style>
    .logo-brand {
        margin: 0 0 20px 0;
    }

    .logo-brand img {
        width: 250px;
        height: auto;
    }
</style>
</head>

<body class="theme-black">
<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="row">
                <div class="col-12">
                    <div class="company_detail">
                        <div class="logo-brand"><img src="./img/logo-multi2.png" alt=""></div>
                        <!-- <h4 class="logo"> </h4><img src="assets/images/logo.svg" alt=""> Gestão -->
                    </div>
                </div>
            </div>
            <div class="row flex-md-row-reverse">
            <div class="col-lg-5 col-md-12 offset-lg-1">
                    <div class="card-plain">
                        <div class="header">
                            <h5>Preencha o cadastro</h5>
                            <span>Dados do responsável pelos participantes</span>
                        </div>
                        <form class="form" method="post" action="busca-reserva.php">                            
                            <div class="input-group">
                                <input name="cpf" type="text" class="form-control" placeholder="CPF" required>
                                <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            </div>
                            <div class="blocks">
                                <div class="input-group">
                                    <input name="nome" type="text" class="form-control" placeholder="Nome" required>
                                    <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                                </div>
                                <div class="input-group">
                                    <input name="email" type="text" placeholder="E-mail" class="form-control" required />
                                    <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                                </div>
                                <div class="input-group">
                                    <input name="telefone" type="text" placeholder="Telefone" class="form-control" required />
                                    <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                                </div>              
                            </div>              
                            <div class="checkbox">
                                <input id="termos" type="checkbox" name="termos" required>
                                <label for="termos">Li e concordo com as  <a href="" id="regras">regras e termos de uso dos dados</a>.</label>
                            </div>                            
                        
                            <div class="footer">
                                <input type="hidden" name="hashevento" value="<?= $_GET['i'] ?>">
                                <button type="submit" class="btn btn-primary btn-round btn-block">Continuar</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h3><?= $dadosEvento['titulo'] ?></h3>
                        <p>Realize o seu cadastro aqui para facilitar o procedimento de acesso ao parque.</p>                        
                        <div class="footer">
                            <ul  class="social_link list-unstyled">
                                <li><a href="https://www.linkedin.com/" title="LinkedIn"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://www.facebook.com/" title="Facebook"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="http://twitter.com/" title="Twitter"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="http://plus.google.com/" title="Google plus"><i class="zmdi zmdi-google-plus"></i></a></li>
                            </ul>
                            <hr>
                            <ul>
                                <li><a href="" target="_blank">Email</a></li>
                                <li><a href="" target="_blank">Sobre nós</a></li>
                                <li><a href="" target="_blank">FAQ</a></li>
                            </ul>
                        </div>
                    </div>                    
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 
<script src="./assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
<script>
    $(document).ready(function(){
        $('#regras').click(function(e){
            e.preventDefault();
            // alert('ok')
            swal({
                title: "HTML <small>Title</small>!",
                text: "<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f7f7f7; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);\"><h2 style=\"color: #333;\">Regras e Termos de Uso</h2><p style=\"color: #666;\">Ao utilizar este serviço, você concorda com os seguintes termos:</p><ol style=\"color: #666; margin-left: 20px;\"><li>Os usuários devem respeitar os direitos de propriedade intelectual de terceiros.</li><li>Não é permitido utilizar este serviço para atividades ilegais ou antiéticas.</li><li>O conteúdo gerado pelos usuários deve ser adequado e respeitoso.</li><li>Os usuários são responsáveis por manter a segurança de suas contas.</li></ol><p style=\"color: #666;\">Ao continuar, você concorda em cumprir estas regras e termos de uso.</p></div>",
                html: true
            });
        });

        $('input[name=cpf]').change(function(){
            $('.blocks input, button').prop('readonly', true);
            let cpf = $(this).val();
            $.post('./blocos/busca-dados.php', {cpf:cpf}, function(data){
                let dados = JSON.parse(data);
                console.log(data);
                if (Array.isArray(dados) && dados.length > 0) {
                    $('input[name=nome]').val(dados[0].nome);
                    $('input[name=email]').val(dados[0].email);
                    $('input[name=telefone]').val(dados[0].telefone1);
                    $('button').prop('readonly', false);
                } else {
                    $('input[name=nome]').val('');
                    $('input[name=email]').val('');
                    $('input[name=telefone]').val('');

                    $('input, button').prop('readonly', false);
                }

                
            })
        })
    })
</script>
</body>
</html>