<div class="modal fade" id="modalSaida" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="pagamento-saida.php" method="post" class="form-modal-controle">
                <div class="modal-header">
                    <h4 class="title" id="modalSaidaLabel">Saída</h4>
                </div>
                <div class="modal-body">

                    <div class="body table-responsive">
                        <table class="table table-bordered">
                            
                            <tbody>
                                <tr>
                                    <td scope="row" width="20%">Nome</td>
                                    <td width="80%" id="nomeresponsavel"></td>
                                </tr>
                                <tr>
                                    <td scope="row">Telefone 1</td>
                                    <td id="tel1"></td>
                                </tr>
                                <tr>
                                    <td scope="row">Telefone 2</td>
                                    <td id="tel2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="tabelaDados" class="body table-responsive my-3"></div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="tipopgto" value="2">
                    <input type="hidden" id="idprevenda" name="idprevenda" value="">
                    <input type="hidden" id="tempo_agora" name="tempo_agora" value="">
                    <button type="submit" class="btn btn-default btn-round waves-effect">Efetuar saída</button>
                    <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    // Intercepta o envio do formulário
    $('.form-modal-controle').submit(function(event) {
        // Verifica se pelo menos um checkbox está selecionado
        
        // alert($('input[type=checkbox]:checked').length);
        // event.preventDefault();
        
        if ($('input[type=checkbox]:checked').length === 2) {
            // Mostra um alerta se nenhum checkbox estiver selecionado
            swal("Erro", "Selecione ao menos um item para avançar!", "error");
            // Impede o envio do formulário
           event.preventDefault();
        }
        
    });
});
</script>
