
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <h5>Pacotes do evento</h5>
                <hr>
                
                    
                    <form action="" method="post" id="form-pacotes">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome do pacote</label>                               
                                    <input type="text" class="form-control" placeholder="" name="descricao" required  />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Valor (R$)</label>                               
                                    <input type="text" class="form-control" placeholder="" name="valor" required  />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Duração (min)</label>                               
                                    <input type="text" class="form-control" placeholder="" name="duracao" required  />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Tolerância</label>                               
                                    <input type="text" class="form-control" placeholder="" name="tolerancia" required  />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Valor 1min adicional</label>                               
                                    <input type="text" class="form-control" placeholder="" name="adicional" required  />
                                </div>
                            </div>
                            
                                <div class="col-12">
                                    <input type="hidden" value="<?= $_GET['id'] ?>" name="evento">
                                    <button type="submit" class="btn btn-info btn-round waves-effect">Adicionar pacote</button>
                                </div>
                            <!-- </div> -->

                        </div>
                    </form>
                    

                <div class="row mt-4">
                    <div class="col-12 table-responsive lista-pacotes">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        let idevento = <?= $_GET['id'] ?>;

        $('.lista-pacotes').load('./blocos/pacotes-lista.php', {id: idevento});
    
        $('#form-pacotes').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            
            $.post('./blocos/pacotes-adiciona.php', formAtual.serialize(), function(data) {
                $('.lista-pacotes').load('./blocos/pacotes-lista.php', {id: idevento});
            })
        });

        $('body').on('click', '.excluipacote', function(e){
            e.preventDefault();
            let id = $(this).data('id');

            swal({
                title: "Excluir pacote?",
                text: "Deseja realmente excluir este pacote?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, excluir!",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    // swal("Excluído!", "O pacote foi excluído.", "success");
                    $.post('./blocos/pacotes-exclui.php', {id: id}, function(data) {
                        $('.lista-pacotes').load('./blocos/pacotes-lista.php', {id: idevento});
                    });
                }
            });
            
        });
    });
</script>