<div class="modal fade" id="addResponsavelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form action="./blocos/adiciona-novo-responsavel.php" method="post"  >
            <div class="modal-header">
                <h4 class="title" id="addResponsavelModalLabel">Pré venda</h4>
            </div>
            <div class="modal-body">
            
                        <h5 class="card-inside-title">Dados do responsável</h5>
                        <div class="row clearfix">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" value="" id="cpf" required />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome</label>                            
                                    <input  id="nome" name="nome" type="text" class="form-control" placeholder="Nome" value="" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telefone1" class="form-label">Telefone 1</label>
                                    <input id="telefone1" name="telefone1" type="text" class="form-control" placeholder="Telefone 1" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telfone2" class="form-label">Telefone 2</label>
                                    <input id="telefone2" name="telefone2" type="text" class="form-control" placeholder="Telefone 2" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Email</label>
                                    <input id="email" name="email" type="text" class="form-control" placeholder="Email" value="" required />
                                </div>
                            </div> 

                            
                        </div>                       
                        
            </div>
            <div class="modal-footer">
                <input type="hidden" id="idresponsavel" name="idresponsavel" value="">
                <button type="submit" class="btn btn-default btn-round waves-effect">Salvar e avançar</button>
                <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        $('body').on('change', '#cpf', function(){
            let i = $(this).val();
            $.post('./blocos/procura-responsavel.php', {id:i}, function(data){
                $('form')[0].reset();
                $('#cpf').val(i);
                if (data==0) {
                    // console.log('não existe');
                    $('#idresponsavel').val('');
                } else {
                    let dados = JSON.parse(data);
                    $('#idresponsavel').val(dados[0].id_responsavel);
                    $('#nome').val(dados[0].nome);
                    $('#telefone1').val(dados[0].telefone1);
                    $('#telefone2').val(dados[0].telefone2);
                    $('#email').val(dados[0].email);
                }
            })
        })
    })
</script>