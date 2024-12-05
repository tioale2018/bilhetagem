<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <form action="" method="post" id="form-basico">
                    <h5>Dados básicos do evento</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="titulo" class="form-label">Título</label>                               
                                <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['titulo'] ?>" name="titulo"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">Local</label>                               
                                <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['local'] ?>" name="local"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">Cidade</label>                               
                                <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['cidade'] ?>" name="cidade"  />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="form-label">Data de início</label>                               
                                <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="<?= date('Y-m-d',$row_busca_evento[0]['inicio']); ?>" name="inicio"  />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="form-label">Data de fim (estimada)</label>                               
                                <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="<?= $row_busca_evento[0]['fim']==''?'':date('Y-m-d',$row_busca_evento[0]['fim']); ?>" name="fim"  />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="form-label">Capacidade</label>                               
                                <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['capacidade'] ?>" name="capacidade"  />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Status</label>                               
                                <select class="form-control show-tick p-0" name="status">
                                    <option value="1" <?= $row_busca_evento[0]['status']==1?'selected':'' ?>>Em edição</option>
                                    <option value="2" <?= $row_busca_evento[0]['status']==2?'selected':'' ?>>Em execução</option>
                                    <option value="3" <?= $row_busca_evento[0]['status']==3?'selected':'' ?>>Encerrado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-info btn-round waves-effect">Salvar</button>
                        <input type="hidden" name="evento" value="<?= $id ?>">
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#form-basico').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/evento-basico-atualiza.php', formAtual.serialize(), function(data){
                let jsonResponse = JSON.parse(data);
                if (jsonResponse.status == 1) {
                    swal({
                        title: "Mensagens evento",
                        text: "Salvo com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    });
                }
            });
        });
    });

</script>