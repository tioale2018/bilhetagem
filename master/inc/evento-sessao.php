<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <h5>Sessão e atualização</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="form-label">Tempo de sessão (segundos)</label>                               
                            <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['tempo_tela'] ?>" name=""  />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="form-label">Mostra tempo de sessão</label>                               
                            <select name="" class="form-control" id="">
                                <option value="1" <?= $row_busca_evento[0]['mostra_tempo']==1?'selected':'' ?>>Sim</option>
                                <option value="2" <?= $row_busca_evento[0]['mostra_tempo']==2?'selected':'' ?>>Nao</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="atualizatab" class="form-label">Atualização da lista de controle (segundos)</label>                               
                            <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['tempo_atualiza'] ?>" name="atualizatab"  />
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-info btn-round waves-effect">Salvar</button>
                    <input type="hidden" name="evento" value="<?= $id ?>">
                </div>
            </div>
        </div>
    </div>
</div>