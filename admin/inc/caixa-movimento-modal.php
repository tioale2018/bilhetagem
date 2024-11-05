<!-- Large Size -->
<div class="modal fade" id="modalAddMovimento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalMovimento">
                <div class="modal-header">
                    <h4 class="title" id="modalAddMovimentoLabel">Adicionar Movimento</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome" class="form-label">Item</label>                               
                                <input name="nome" type="text" class="form-control" placeholder="Nome" required tabindex="1"/>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Valor</label>                            
                                <input name="nascimento" type="text" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required placeholder="" tabindex="3"/>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome" class="form-label">Descricão</label>   
                                <textarea name="descricao" class="form-control" id="" placeholder="Descreva a movimentação" tabindex="2"></textarea>                            
                                <!-- <input name="nome" type="text" class="form-control" placeholder="Nome" required /> -->
                            </div>
                        </div>
                         
                    </div>   
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idresponsavel" value="<?= $row[0]['id_responsavel'] ?>">
                    <input type="hidden" name="idprevenda" value="<?= $row[0]['id_prevenda'] ?>">
                    <button type="submit" class="btn btn-default btn-round waves-effect addMovimento">Adicionar</button>
                    <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
