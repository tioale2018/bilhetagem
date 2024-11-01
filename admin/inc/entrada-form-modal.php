<!-- Large Size -->
<div class="modal fade" id="modalAddParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalParticipante">
                <div class="modal-header">
                    <h4 class="title" id="modalAddParticipanteLabel">Adicionar participante</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome</label>                               
                                <input name="nome" type="text" class="form-control" placeholder="Nome" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Nascimento</label>                            
                                <input name="nascimento" type="text" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required placeholder="dd/mm/aaaa"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">Tipo de vínculo</label>                            
                                <select name="vinculo" class="form-control show-tick p-0" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_vinculos'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_vinculo'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">Perfil</label>                            
                                <select class="form-control show-tick p-0" name="perfil" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_perfis'] as $k => $v) { ?>
                                        <option  value="<?= $v['idperfil'] ?>"><?= $v['titulo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Pacote</label>                            
                                <select class="form-control show-tick p-0" name="pacote" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_pacotes'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_pacote'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                   
                                </select>
                            </div>
                        </div>  -->
                        <div class="col-12">
                            <div class="form-group">
                                
                                <div class="checkbox">
                                    <input id="lembrarme" name="lembrarme" type="checkbox" value="1">
                                    <label for="lembrarme">Lembrar este participante?</label>
                                </div>
                            </div>
                        </div> 
                    </div>   
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idresponsavel" value="<?= $row[0]['id_responsavel'] ?>">
                    <input type="hidden" name="idprevenda" value="<?= $row[0]['id_prevenda'] ?>">
                    <button type="submit" class="btn btn-default btn-round waves-effect addparticipante">Salvar e adicionar</button>
                    <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('select').selectpicker();   


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
    $('input[name=nascimento]').on('change', function() {
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
    $('input[name=nascimento]').trigger('change');

   
    $('input[name=nascimento]').mask('00/00/0000');

    // Validação personalizada para verificar se a data é válida
    $('input[name=nascimento]').on('input blur', function() {
        var date = $(this).val();
        if (!isValidDate(date) && date.length === 10) {
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid');
        }
    });

    function isValidDate(dateString) {
        var parts = dateString.split("/");
        if (parts.length !== 3) return false;

        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10) - 1; // meses são baseados em zero
        var year = parseInt(parts[2], 10);

        var date = new Date(year, month, day);
        return date.getFullYear() === year && date.getMonth() === month && date.getDate() === day;
    }
        
        
        $('#formModalParticipante').submit(function(event){
            event.preventDefault();
            $('#formModalParticipante button[type="submit"]').prop('disabled', true);
            let Form = $(this).serialize();

            var dateInput = $('input[name=nascimento]').val();
            if (!isValidDate(dateInput)) {
                
                $('input[name=nascimento]').val('');
                alert('Por favor, insira uma data de nascimento válida no formato dd/mm/aaaa.');
                $('input[name=nascimento]').focus();
                $('#formModalParticipante button[type="submit"]').prop('disabled', false);
            } else {

                $.post( "./blocos/add-participante.php", Form, function(data){
                    $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> }, function(){
                        location.reload();
                    });
                    // $('#formModalParticipante button[type="submit"]').prop('disabled', false);
                    // $('#formModalParticipante').trigger('reset');
                    // $('#modalAddParticipante').modal('hide');
                }); 
            }


        });

        $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
            $('#formModalParticipante').trigger('reset');
        })
 
    });    
</script>