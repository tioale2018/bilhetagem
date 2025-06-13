<div class="modal fade" id="modalEditaResp" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form action="" method="post"  class="js-sweetalert" id="formResponsavel">
            <div class="modal-header">
                <h4 class="title">Dados do responsável principal</h4>
            </div>

            <div class="modal-body">           
                        
                        
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>                               
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" value="<?= formatarCPF($id) ?>" maxlength="14" pattern="\d*" readonly />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome completo</label>                            
                                    <input name="nome" type="text" class="form-control" placeholder="Nome" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['nome']:'') ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 1</label>                            
                                    <input name="telefone1" type="text" class="form-control" placeholder="Telefone 1" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['telefone1']:'') ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 2</label>                            
                                    <input name="telefone2" type="text" class="form-control" placeholder="Telefone 2" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['telefone2']:'') ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Email</label>                            
                                    <input name="email" type="text" class="form-control" placeholder="Email" required value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['email']:'') ?>" required />
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Endereço completo</label>                            
                                    <input name="endereco" type="text" class="form-control" placeholder="Endereço completo" required value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['endereco']:'') ?>" required />
                                </div>
                            </div> 
                            <!-- bairro e cidade -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Bairro</label>                            
                                    <input name="bairro" type="text" class="form-control" placeholder="Bairro" required value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['bairro']:'') ?>" required />
                                </div>

                            </div>                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Cidade</label>                            
                                    <input name="cidade" type="text" class="form-control" placeholder="Cidade" required value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['cidade']:'') ?>" required />
                                </div>

                        </div>                   
                       
            </div>

            <div class="modal-footer">
                <div class="row">
                <div class="col-md-12">
                        <div class="form-group">   
                            <button type="button" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-round waves-effect btsalvar" name="btsalvaresponsavel" disabled>Salvar e adicionar</button>                                
                            <!-- <button class="btn btn-raised btn-primary waves-effect btn-round " type="submit" disabled>Salvar</button>                                 -->
                        </div>
                    </div> 
                </div>

            </div>

            </form>

        </div>
    </div>
</div>