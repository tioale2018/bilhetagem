<!-- Large Size -->
<div class="modal fade" id="modalAddParticipante" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <h4 class="title" id="modalAddParticipanteLabel">Adicionar participante</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome</label>                               
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
                                <label for="" class="form-label">Pacote</label>                            
                                <select class="form-control show-tick p-0" name="pacote" id="fpacote">
                                    <option value="">Escolha</option>
                                    <?php foreach ($_SESSION['lista_pacotes'] as $k => $v) { ?>
                                        <option  value="<?= $v['id_pacote'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                </select>
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
        $('#modalAddParticipante form').submit(function(event){
            event.preventDefault();
            let Form = $(this).serialize();
            $.post( "./blocos/add-participante.php", Form, function(data){
                $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> });
                 console.log(data);
                $('#modalAddParticipante').modal('hide');
            }); 
        });
        

        $('#modalAddParticipante').on('hidden.bs.modal', function (e) {
            $('#modalAddParticipante form').trigger('reset');
        })
        
    });    
</script>