<?php
/*
if(!isset($_POST['hashevento'])) {
    header('Location: index.php');
} else {
    $hash = $_POST['hashevento'];
}
*/
$hash = $_POST['hashevento'];
include_once("./inc/conexao.php");
include_once("./inc/funcoes.php");

//valida se é o hash do evento
$sql = "select tbativa.hash, tbativa.idevento, tbevento.titulo, tbevento.local, tbevento.modo_pgto
from tbativa 
inner join tbevento on tbativa.idevento=tbevento.id_evento
where tbativa.ativo=1 and tbevento.status=2 and tbativa.hash=:hash";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':hash', $hash, PDO::PARAM_STR);
$pre->execute();

if ($pre->rowCount()<1) {
    header('Location: index.php');
} else {
    $row = $pre->fetchAll();
    $_SESSION['row'] = $row;
    $evento_atual = $row[0]['idevento'];
    $_SESSION['evento_atual'] = $row[0]['idevento'];
}


//---------------------------

/*
if (isset($_POST['btnFinaliza'])) {
    $datahora = time();
    $idprevenda=$_POST['idprevenda'];
    $sql_status_prevenda = "update tbprevenda set prevenda_status=1, datahora_solicita=:datahora  where prevenda_status=9 and id_prevenda=:idprevenda";

    $pre_status_prevenda = $connPDO->prepare($sql_status_prevenda);
    $pre_status_prevenda->bindParam(':idprevenda', $idprevenda, PDO::PARAM_INT);
    $pre_status_prevenda->bindParam(':datahora', $datahora, PDO::PARAM_STR);
    $pre_status_prevenda->execute();

    header('Location: index.php');
}
*/

//---------------------------------------------------------------------------------------------
$evento = $evento_atual;
//procedimento de busca dos pacotes deste evento
$sql_busca_pacote = "select * from tbpacotes where ativo=1 and id_evento=".$evento;
$pre_busca_pacote = $connPDO->prepare($sql_busca_pacote);
$pre_busca_pacote->execute();
$row_busca_pacote = $pre_busca_pacote->fetchAll();

$_SESSION['lista_pacotes'] = $row_busca_pacote;
//---------------------------------------------------------------------------------------------

//procediemnto de busca de tipo de vínculos
$sql_busca_vinculo = "select * from tbvinculo where ativo=1";
$pre_busca_vinculo = $connPDO->prepare($sql_busca_vinculo);
$pre_busca_vinculo->execute();
$row_busca_vinculo = $pre_busca_vinculo->fetchAll();

$_SESSION['lista_vinculos'] = $row_busca_vinculo;
//---------------------------------------------------------------------------------------------



$id               = limparCPF($_POST['cpf']);
$cpf              = $id;
$_SESSION['cpf']  = $id;

$datahora         = time();
$hoje             = date('Y-m-d', $datahora);
$crianovaPrevenda = false;



$dados_responsavel = procuraResponsavel($id);


//if ($pre_responsavel->rowCount()>0) {
if ($dados_responsavel!=false) {    
    // $dados_responsavel = $pre_responsavel->fetchAll();
    // verifica se tem pre-venda nao concluida
    $idResponsavel = $dados_responsavel[0]['id_responsavel'];

    $sql_busca_prevenda = "select * from tbprevenda where id_evento=$evento_atual and prevenda_status=9 and id_responsavel=$idResponsavel order by id_prevenda limit 1";
    $pre_busca_prevenda = $connPDO->prepare($sql_busca_prevenda);
    $pre_busca_prevenda->execute();

    if ($pre_busca_prevenda->rowCount()>0) {
        $row_busca_prevenda = $pre_busca_prevenda->fetchAll();
        $idPrevendaAtual = $row_busca_prevenda[0]['id_prevenda'];
    } else {
        $crianovaPrevenda = true;
    }

} else {
    //echo "0";
    //insere o responsavel
    $nome      = $_POST['nome'];
    $email     = $_POST['email'];
    $telefone1 = $_POST['telefone'];
    
    $sql_insere_responsavel = "insert into tbresponsavel (nome, cpf, email, telefone1, datahora_input) values (:nome, :cpf, :email, :telefone1, :datahora_input)";

    $pre_insere_responsavel = $connPDO->prepare($sql_insere_responsavel);

    $pre_insere_responsavel->bindParam(':cpf', $cpf, PDO::PARAM_INT);
    $pre_insere_responsavel->bindParam(':nome', $nome, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':email', $email, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':telefone1', $telefone1, PDO::PARAM_STR);
    $pre_insere_responsavel->bindParam(':datahora_input', $datahora, PDO::PARAM_STR);
    

    $pre_insere_responsavel->execute();

    $idResponsavel = $connPDO->lastInsertId();

    $crianovaPrevenda = true;

    $dados_responsavel = procuraResponsavel($id);
}


if ($crianovaPrevenda) {
    $sql_prevenda = "insert into tbprevenda (id_responsavel, id_evento, data_acesso, prevenda_status, datahora_solicita) values (:id_responsavel, :id_evento, :data_acesso, 9, :datahora_solicita)";

    $pre_prevenda = $connPDO->prepare($sql_prevenda);

    $pre_prevenda->bindParam(':id_responsavel', $idResponsavel, PDO::PARAM_INT);
    $pre_prevenda->bindParam(':id_evento', $evento_atual, PDO::PARAM_INT);
    $pre_prevenda->bindParam(':data_acesso', $hoje, PDO::PARAM_STR);
    $pre_prevenda->bindParam(':datahora_solicita', $datahora, PDO::PARAM_STR);

    $pre_prevenda->execute();

    $idPrevendaAtual = $connPDO->lastInsertId();
    
}
$_SESSION['idPrevenda']       = $idPrevendaAtual;
$_SESSION['dadosResponsavel'] = $dados_responsavel;


?>
