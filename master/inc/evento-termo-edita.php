<?php
$sql_buscatermo = "select * from tbtermo where ativo=1 and idevento=$id";
// die($sql_buscatermo);
$pre_buscatermo = $connPDO->prepare($sql_buscatermo);
$pre_buscatermo->execute();
$row_buscatermo = $pre_buscatermo->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <h5>Termo de aceite</h5>
                <hr>

                <div class="row">
                    <div class="col-12">

                        <form action="" method="post" id="form-termo" class="row">

                            <div class="col-12">
                                <textarea name="textotermo" id="conteudotermo" class="form-control"  rows="20"><?= $row_buscatermo[0]['textotermo'] ?></textarea>
                            </div>
                            <div class="col-4 mt-3">
                                <div class="form-group">
                                    <label for="atualizatab" class="form-label">Cidade do termo</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_buscatermo[0]['cidadetermo'] ?>" name="cidade"  />
                                </div>
                            </div>
                            <div class="col-4 mt-3">
                                <div class="form-group">
                                    <label for="atualizatab" class="form-label">Empresa do termo</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_buscatermo[0]['empresa'] ?>" name="empresa"  />
                                </div>
                            </div>
                            <div class="col-4 mt-3">
                                <div class="form-group">
                                    <label for="atualizatab" class="form-label">CNPJ da empresa</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_buscatermo[0]['cnpj'] ?>" name="cnpj"  />
                                </div>
                            </div>
                            

                            <div class="col-12">
                                <button type="submit" class="btn btn-info btn-round waves-effect salvatermo">Salvar</button>
                                <input type="hidden" name="idtermo" value="<?= $row_buscatermo[0]['idtermo'] ?>">
                            </div>

                        </form>

                    </div>
                </div>

                

                        <div class="row clearfix pt-3">
                            <div class="col-md-12 col-lg-12">
                                <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">

                                    <div class="panel panel-primary">
                                        <div class="panel-heading" role="tab" id="headingOne_1">
                                            <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseOne_1" aria-expanded="true" aria-controls="collapseOne_1"> Variáveis disponíveis </a> </h4>
                                        </div>
                                        <div id="collapseOne_1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_1">
                                            <div class="panel-body">
                                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Variável</th>
                                        <th>Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{responsavelnome}}</td>
                                        <td>Nome do responsável</td>
                                    </tr>
                                    <tr>
                                        <td>{{responsavelcpf}}</td>
                                        <td>CPF do responsável</td>
                                    </tr>
                                    <tr>
                                        <td>{{responsaveltel1}}</td>
                                        <td>Telefone do responsável</td>
                                    </tr>
                                    <tr>
                                        <td>{{participantenome}}</td>
                                        <td>Nome do participante</td>
                                    </tr>
                                    <tr>
                                        <td>{{participantenascimento}}</td>
                                        <td>Data de nascimento do participante</td>
                                    </tr>
                                    <tr>
                                        <td>{{participanteidade}}</td>
                                        <td>Idade do participante</td>
                                    </tr>
                                    <tr>
                                        <td>{{datahoje}}</td>
                                        <td>Data de hoje</td>
                                    </tr>
                                    <tr>
                                        <td>{{cidadetermo}}</td>
                                        <td>Cidade do termo</td>
                                    </tr>
                                    <tr>
                                        <td>{{empresatermo}}</td>
                                        <td>Cidade do termo</td>
                                    </tr>
                                    <tr>
                                        <td>{{cnpjtermo}}</td>
                                        <td>Cidade do termo</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    
              
        
            </div>
        </div>
    </div>
</div>





<script>
    $(document).ready(function(){

        $('#form-termo').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            tinymce.remove('textarea');

            $.post('./blocos/termo-salvar.php', formAtual.serialize(), function(data){
                let jsonResponse = JSON.parse(data);
                console.log(jsonResponse.status);
                if (jsonResponse.status == 1) {
                    swal({
                        title: "Termo de aceite",
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
        });
    });
   
</script>