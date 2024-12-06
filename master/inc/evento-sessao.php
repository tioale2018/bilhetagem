<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <form action="" method="post" id="form-sessao">

                <h5>Sessão e atualização</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="form-label">Tempo de sessão (segundos)</label>                               
                            <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['tempo_tela'] ?>" name="sessao"  />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="form-label">Mostra tempo de sessão</label>                               
                            <select name="mostratempo" class="form-control" id="">
                                <option value="1" <?= $row_busca_evento[0]['mostra_tempo']==1?'selected':'' ?>>Sim</option>
                                <option value="2" <?= $row_busca_evento[0]['mostra_tempo']==2?'selected':'' ?>>Nao</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="atualizatab" class="form-label">Atualização da lista de controle (segundos)</label>                               
                            <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['tempo_atualiza'] ?>" name="atualizalista"  />
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
        $('#form-sessao').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/evento-sessao-atualiza.php', formAtual.serialize(), function(data){
                let jsonResponse = JSON.parse(data);
                if (jsonResponse.status == 1) {
                    swal({
                        title: "Dados de sessão do evento",
                        text: "Atualizado com sucesso!",
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