<?php
//lista os tipos de despesa
$evento = $_SESSION['evento_selecionado'];
$sqlTipoDespesa = "SELECT * FROM tbcaixa_tipodespesa WHERE ativo=1 and idevento in (0,$evento)";
$preTipoDespesa = $connPDO->prepare($sqlTipoDespesa);
$preTipoDespesa->execute();
$rowTipoDespesa = $preTipoDespesa->fetchAll();

?>

<!-- Large Size -->
<div class="modal fade" id="modalAddMovimento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" id="formModalMovimento">
                <div class="modal-header">
                    <h4 class="title" id="modalAddMovimentoLabel">Adicionar Movimento</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome" class="form-label">Item</label>                               
                                <input name="nome" type="text" class="form-control" placeholder="Nome" required tabindex="1"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Valor</label>                            
                                <input name="valor" type="number" class="form-control" required placeholder="" tabindex="3"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="form-label">Tipo despesa</label>
                                <select class="form-control p-0" name="tipomovimento" required>
                                    <option value="">Escolha</option>
                                    <?php foreach ($rowTipoDespesa as $k => $v) { ?>
                                        <option value="<?= $v['id'] ?>"><?= $v['descricao'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nome" class="form-label">Descricão</label>   
                                <textarea name="descricao" class="form-control" id="" placeholder="Descreva a movimentação" tabindex="2"></textarea>                            
                                <!-- <input name="nome" type="text" class="form-control" placeholder="Nome" required /> -->
                            </div>
                        </div>
                         
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default btn-round waves-effect addMovimento">Adicionar</button>
                    <button type="button" class="btn btn-danger btn-simple btn-round waves-effect" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#formModalMovimento').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/caixa-movimento-atualiza.php', formAtual.serialize(), function(data){
                // $('#modalAddMovimento').modal('hide');
                // $('.bloco-vinculados').load('./blocos/lista-vinculados.php', {i:<?= $_GET['item'] ?> });
                window.location.reload();
            });
        })
    });
</script>