<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();
include_once('../inc/conexao.php');

$participante = intval($_POST['i']);
$prevenda     = intval($_POST['j']);

// $sql = "update tbentrada set previnculo_status=2 where id_entrada=:entrada";
//$sql = "select * from tbvinculados where id_vinculado=:vinculado";
$sql = "select tbvinculados.id_vinculado, tbentrada.id_entrada, tbvinculados.nome, tbvinculados.nascimento, tbvinculados.tipo, tbentrada.id_pacote, tbvinculados.tipo as id_tipovinculo,  tbvinculados.lembrar, tbentrada.perfil_acesso
from tbvinculados
inner join tbentrada on tbvinculados.id_vinculado=tbentrada.id_vinculado
where tbentrada.previnculo_status=1 and tbentrada.id_prevenda=:prevenda and tbentrada.id_vinculado=:participante";
$pre = $connPDO->prepare($sql);
$pre->bindParam(':participante', $participante, PDO::PARAM_INT);
$pre->bindParam(':prevenda', $prevenda, PDO::PARAM_INT);
$pre->execute();

$row = $pre->fetchAll();

// die(var_dump($_SESSION['lista_vinculos']));

?>
<form action="" method="post" id="formEditaParticipante">
    <div class="modal-header">
        <h4 class="title" id="modalEditaParticipanteLabel">Editar dados participante</h4>
    </div>
    <div class="modal-body"> 
        <div class="row clearfix">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nome" class="form-label">Nome</label>                               
                    <input name="nome" id="fnome" type="text" class="form-control" placeholder="Nome" value="<?= $row[0]['nome'] ?>" required />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Nascimento</label>                            
                    <input name="nascimento" id="fnascimento" type="date" class="form-control" placeholder="Nascimento" value="<?= $row[0]['nascimento'] ?>" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Tipo de vínculo</label>                            
                    <select name="vinculo" class="form-control show-tick p-0" name="vinculo" id="fvinculo">
                        <option value="">Escolha</option>
                        <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                            <option <?= ($v['id_vinculo']==$row[0]['id_tipovinculo']?'selected':'') ?>  value="<?= $v['id_vinculo'] ?>" ><?= $v['descricao'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="form-label">Perfil</label>
                    <select class="form-control p-0" name="perfil" id="fperfil">
                        <option value="">Escolha</option>
                        <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                            <option <?= ($v['idperfil']==$row[0]['perfil_acesso']?'selected':'') ?> value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Pacote</label>                            
                                <select class="form-control show-tick p-0" name="pacote" id="fpacote">
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_pacotes'] as $k => $v) { ?>
                                        <option <?= ($v['id_pacote']==$row[0]['id_pacote']?'selected':'') ?>  value="<?= $v['id_pacote'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                   
                                </select>
                            </div>
                        </div> 
            <div class="col-12">
                <div class="form-group">
                    
                    <div class="checkbox">
                        <input id="melembrar" name="melembrar" type="checkbox" value="1" <?= ($row[0]['lembrar']?'checked':'') ?>>
                        <label for="melembrar">Lembrar este participante?</label>
                    </div>
                </div>
            </div> 
        </div>   
    </div>
    <div class="modal-footer">
        
        <input type="hidden" name="idvinculado" value="<?= $participante ?>">
        <input type="hidden" name="identrada" value="<?= $row[0]['id_entrada'] ?>">
        
        <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" name="btaddparticipante">Salvar e adicionar</button>
        <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
    </div>
</form>

<script>
    $(document).ready(function(){
        $('select').selectpicker();

        $('#formEditaParticipante').submit(function(e){
            e.preventDefault();
            let Form = $(this);
            
            $.post('./blocos/participante-atualiza.php', Form.serialize(), function(data){
                // console.log(data);
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $prevenda ?> }, function(){
                    $('#modalEditaParticipante').modal('toggle');
                    
                });

            });
        });
    });
</script>

<script>
$(document).ready(function() {
    // Função para calcular a idade com base na data de nascimento
    function calculateAge(date) {
        const today = new Date();
        const birthDate = new Date(date);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    // Monitorar alterações no campo de data de nascimento
    $('#fnascimento').on('change', function() {
        const birthDate = $(this).val();
        if (birthDate) {
            const age = calculateAge(birthDate);
            $('#idade').text(age);
            $('#infoNascimento').show();
        } else {
            $('#infoNascimento').hide();
        }
    });

    // Disparar o evento change para lidar com casos onde o campo já está preenchido no carregamento da página
    $('#fnascimento').trigger('change');
});
</script>

