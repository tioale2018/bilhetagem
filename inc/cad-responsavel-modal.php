<div class="modal fade" id="modalResponsavelLegal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <form action="" method="post"  class="js-sweetalert" id="formResponsavelLegal">
            <div class="modal-header">
                <h4 class="title">Responsável Legal Secundário</h4>
            </div>

            <div class="modal-body">           
                        
                        
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cpf" class="form-label">CPF</label>                               
                                    <input name="cpf" type="text" class="form-control" placeholder="CPF" value="<?=  (isset($_SESSION['dadosSecundario'][0]['cpf'])?formatarCPF($_SESSION['dadosSecundario'][0]['cpf']):'') ?>" maxlength="14" pattern="\d*" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="" class="form-label">Nome</label>                            
                                    <input name="nome" type="text" class="form-control" placeholder="Nome" value="<?= (isset($_SESSION['dadosSecundario'][0]['nome'])?$_SESSION['dadosSecundario'][0]['nome']:'') ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="form-label">Telefone</label>                            
                                    <input name="telefone" type="text" class="form-control" placeholder="Telefone" value="<?= (isset($_SESSION['dadosSecundario'][0]['telefone'])?$_SESSION['dadosSecundario'][0]['telefone']:'') ?>" required />
                                </div>
                            </div>
                            

                        </div>                   
                       
            </div>

            <div class="modal-footer">
                <div class="row">
                <div class="col-md-12">
                        <div class="form-group">   
                            <button type="button" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-round waves-effect btsalvaLegal" name="btsalvaLegal" disabled>Salvar e adicionar</button>                                
                            <!-- <button class="btn btn-raised btn-primary waves-effect btn-round " type="submit" disabled>Salvar</button>                                 -->
                        </div>
                    </div> 
                </div>

            </div>

            </form>

        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
       // Máscara e validação do CPF no campo de entrada
        $('input[name="cpf"]').on('input', function() {
            let cpf = $(this).val();
            $(this).val(aplicarMascaraCPF(cpf));
            
            // Validação do CPF
            if (!validarCPF(cpf.replace(/\D/g, ''))) {
                $(this).css('border', '2px solid red'); // Borda vermelha se o CPF for inválido
                $('button[type="submit"]').prop('disabled', true); // Impede o submit
            } else {
                $(this).css('border', ''); // Reseta a borda
                $('button[type="submit"]').prop('disabled', false); // Permite o submit
            }
        });

        // Reseta a borda ao corrigir o CPF
        $('input[name="cpf"]').on('focus', function() {
            $(this).css('border', '');
            let cpf = $(this).val().replace(/\D/g, '');
            $(this).val(aplicarMascaraCPF(cpf));
        });

        // Remove a máscara ao perder o foco
        $('input[name="cpf"]').on('blur', function() {
            let cpf = $(this).val().replace(/\D/g, '');
            $(this).val(cpf);
        });
    });
</script>