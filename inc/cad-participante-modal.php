<!-- Large Size -->
<div class="modal fade" id="modalAddParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalAddParticipante">
                <div class="modal-header">
                    <h4 class="title" id="modalAddParticipanteLabel">Adicionar participante</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome <span id="infoNascimento">(Idade: <span id="idade">2</span> anos</span>)</label>                                
                                <input name="nome" id="fnome" type="text" class="form-control" placeholder="Nome" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Nascimento</label>                            
                                <input name="nascimento" id="fnascimento" type="date" class="form-control" placeholder="Nascimento" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Tipo de vínculo</label>                            
                                <select name="vinculo" class="form-control show-tick p-0" name="vinculo" id="fvinculo">
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_vinculo'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Perfil</label>                            
                                <select class="form-control p-0" name="pacote" id="fpacote">
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                                        <option  value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="checkbox">
                                <input id="lembrarme" name="lembrarme" type="checkbox" value="1">
                                <label for="lembrarme">Lembrar este participante?</label>
                            </div>
                        </div> 
                    </div>   
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idresponsavel" value="<?= $dados_responsavel[0]['id_responsavel']; ?>">
                    <input type="hidden" name="cpf" value="<?= $dados_responsavel[0]['cpf']; ?>">
                    <input type="hidden" name="idprevenda" value="<?= $idPrevendaAtual ?>">
                    <button type="submit" class="btn btn-default btn-round waves-effect addparticipante" name="btaddparticipante">Salvar e adicionar</button>
                    <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalEditaParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="formParticipanteContent">
            
        </div>
    </div>
</div>


<div class="modal fade" id="modalTermoParticipante" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" id="formTermoParticipante">
           
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        
        $('#modalAddParticipante form').submit(function(event){
            event.preventDefault();
            let Form = $(this).serialize();
            let i = 1;

            $.post( "./blocos/add-participante.php", Form, function(data){
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $idPrevendaAtual ?> }, function(){
                    $('#modalAddParticipante').modal('hide');
                });
            }); 


        });
       
        /*
            $.post( "./blocos/add-participante.php", Form, function(data){
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $idPrevendaAtual ?> }, function(){
                    
                    
                    
                    
                });
                //console.log(data);
                //location.reload();
            }); 

            */

        

        $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
            $('#modalAddParticipante form').trigger('reset');
        })
    
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
