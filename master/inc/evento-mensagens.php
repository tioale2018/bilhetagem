<div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h5>Mensagens ao usuário</h5>
                        <hr>
                        
                        <form action="" method="post" id="form-msg">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Term de uso</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cadastro">Cadastro</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parque">Regras do parque</a></li>                        
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reserva">Mensagem final</a></li>                        
                            </ul>                        
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane in active" id="home"> 
                                    <h6>Termo de uso dos dados</h6>
                                    <textarea name="regra_home" class="form-control"  rows="10"><?= $row_busca_evento[0]['regras_home'] ?></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="cadastro"> 
                                    <h6>Regras de cadastro</h6>
                                    <textarea name="regra_cadastro" class="form-control"  rows="10"><?= $row_busca_evento[0]['regras_cadastro'] ?></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="parque"> 
                                    <h6>Regras do parque</h6>
                                    <textarea name="regra_parque" class="form-control"  rows="10"><?= $row_busca_evento[0]['regras_parque'] ?></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="reserva"> 
                                    <h6>Mensagem final de cadastro</h6>
                                    <textarea name="regra_reserva" class="form-control"  rows="10"><?= $row_busca_evento[0]['msg_fimreserva'] ?></textarea>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-info btn-round waves-effect salva-msgs">Salvar</button>
                                <input type="hidden" name="evento" value="<?= $id ?>">
                            </div>

                        </form>
                
                    </div>
                </div>
            </div>
        </div>
        
       
<script>
    $(document).ready(function() {
        $('#form-msg').submit(function(e) {
            e.preventDefault();
            let formAtual = $(this);
            tinymce.remove('textarea');

            document.querySelectorAll('textarea').forEach(input => {
                let t = input.value;
                input.value = t.replace(/(\r\n|\n|\r)/gm, "");
            })

            $.post('./blocos/mensagens-salvar.php', formAtual.serialize(), function(data){
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
                    }, function () {
                        aplicaTiny();
                    });
                }
            });
        })
    });
</script>

