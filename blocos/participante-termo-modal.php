<?php
if ($_SERVER['REQUEST_METHOD']!="POST" || (!isset($_POST['i'])) || (!is_numeric($_POST['i']))) {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
session_start();

include('../inc/conexao.php');

function replaceVariables($text, $variables) {
    foreach ($variables as $key => $value) {
        // Cria o padrão da variável, por exemplo, {{var1}}
        $pattern = '{{' . $key . '}}';
        // Substitui todas as ocorrências do padrão no texto pelo valor correspondente
        $text = str_replace($pattern, $value, $text);
    }
    return $text;
}

function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}
/*
function formatDate($timestamp) {
    // Define o locale para português
    setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese', 'pt_BR.utf8');
    
    // Formata a data usando strftime
    $formattedDate = strftime('%d de %B de %Y', $timestamp);
    
    // Retorna a data formatada
    return $formattedDate;
}

*/

function formatDate($timestamp) {
    // Array com os nomes dos meses em português
    $months = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];
    
    // Extrai o dia, mês e ano do timestamp
    $day = date('d', $timestamp);
    $month = date('n', $timestamp);
    $year = date('Y', $timestamp);

    // Monta a data formatada
    $formattedDate = sprintf('%d de %s de %d', $day, $months[$month], $year);
    
    // Retorna a data formatada
    return $formattedDate;
}

$sql_busca_termo = "select tbtermo.*, tbevento.titulo, tbevento.local, tbevento.cidade from tbtermo 
inner join tbevento on tbevento.id_evento=tbtermo.idevento
where tbtermo.ativo=1 and tbtermo.idtermo=".$_SESSION['evento_atual'];
$pre_busca_termo = $connPDO->prepare($sql_busca_termo);
$pre_busca_termo->execute();
$row_busca_termo = $pre_busca_termo->fetchAll();



$identrada = $_POST['i'];
$sql_dados_participante = "SELECT tbentrada.id_prevenda, tbvinculados.nome as participantenome, tbvinculados.nascimento, tbresponsavel.nome as responsavelnome, tbresponsavel.cpf, tbresponsavel.telefone1, tbresponsavel.email FROM tbentrada inner join tbvinculados on tbvinculados.id_vinculado=tbentrada.id_vinculado inner join tbresponsavel on tbresponsavel.id_responsavel=tbvinculados.id_responsavel WHERE tbentrada.id_entrada=:identrada";

$pre_dados_participante = $connPDO->prepare($sql_dados_participante);
$pre_dados_participante->bindParam(':identrada', $identrada, PDO::PARAM_INT);
$pre_dados_participante->execute();
$row_dados_participante = $pre_dados_participante->fetchAll();

// die(var_dump($row_dados_participante));

// Exemplo de uso
//$text = "Olá, meu nome é {{nome}} e eu tenho {{idade}} anos.";
$dataAgora = time();

$variables = [
    'responsavelnome' => $row_dados_participante[0]['responsavelnome'],
    'responsavelcpf' => $row_dados_participante[0]['cpf'],
    'responsaveltel1' => $row_dados_participante[0]['telefone1'],
    'participantenome' => $row_dados_participante[0]['participantenome'],
    'participantenascimento' => $row_dados_participante[0]['nascimento'],
    'participanteidade' => calculateAge($row_dados_participante[0]['nascimento']),
    'datahoje' => formatDate($dataAgora),
    'cidadetermo' => $row_busca_termo[0]['cidade']
];


?>
<form action="" id="formAceitaTermo" method="post">
    <div class="modal-header">
        <h4 class="title" id="modalTermoParticipanteLabel">Termo de consentimento</h4>
    </div>
    <div class="modal-body"> 
        <div class="row clearfix">
            <div class="col-md-12">
                <?= replaceVariables($row_busca_termo[0]['textotermo'], $variables); ?>
                <div class="">
                    <input id="assinatermo" name="assinatermo" type="checkbox" value="1" required>
                    <label for="assinatermo">Confirmo que li o termo e estou de acordo com suas condições.</label>
                </div>
            </div>
        </div>   
    </div>
    <div class="modal-footer">
        <input type="hidden" name="participante" value="<?= $identrada ?>">
        <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" name="btaddparticipante">Salvar e autorizar</button>
    </div>
</form>


<script>
    $(document).ready(function(){
        $('#formAceitaTermo').submit(function(e){
            e.preventDefault();
            let Form = $(this).serialize();
            $.post('./blocos/aceita-termo.php', Form, function(data){
                console.log(data);
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i: <?= $row_dados_participante[0]['id_prevenda'] ?>}, function(){
                    $('#modalTermoParticipante').modal('hide');
                });
                

            })
        })
    })
</script>


