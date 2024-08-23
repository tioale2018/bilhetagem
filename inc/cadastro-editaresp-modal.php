<div class="modal fade" id="modalEditaResp" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form action="" method="post"  class="js-sweetalert" id="formResponsavel">
            <div class="modal-header">
                <h4 class="title">Editar dados do responsável</h4>
            </div>

            <div class="modal-body">           
                        
                        <?php // (isset($dados_responsavel)?'<div style="color:tomato">Dados localizados</div>':'') ?>
                        <div class="row clearfix">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>                               
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" value="<?= formatarCPF($id) ?>" maxlength="14" pattern="\d*" readonly />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome</label>                            
                                    <input name="nome" type="text" class="form-control" placeholder="Nome" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['nome']:'') ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone 1</label>                            
                                    <input name="telefone1" type="text" class="form-control" placeholder="Telefone 1" value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['telefone1']:'') ?>" />
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
                                    <input name="email" type="text" class="form-control" placeholder="Email" required value="<?= (isset($dados_responsavel)?$dados_responsavel[0]['email']:'') ?>" />
                                </div>
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


       


<?php /*

            <form action="" method="post" id="formModalEditaResp">
                <div class="modal-header">
                    <h4 class="title" id="modalEditaRespLabel">Adicionar participante</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="fnome" class="form-label">Nome <span id="infoNascimento">(Idade: <span id="idade">2</span> anos)</span></label>                                
                                <input name="nome" id="fnome" type="text" class="form-control" placeholder="Nome" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fanscimento" class="form-label">Nascimento</label>                            
                                <input name="nascimento" id="fnascimento" type="text" placeholder="dd/mm/aaaa" class="form-control" placeholder="Nascimento" pattern="\d{2}/\d{2}/\d{4}" required />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fvinculo" class="form-label">Tipo de vínculo</label>                            
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
                                <label for="fpacote" class="form-label">Perfil</label>                            
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
                    <button type="button" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-round waves-effect EditaResp" name="btEditaResp">Salvar e adicionar</button>
                </div>
            </form>


            <?php */ ?>
        </div>
    </div>
</div>