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
                
                <form action="" method="post" id="form-termo" class="row">

                    <div class="col-12">
                        <textarea name="textotermo" class="form-control"  rows="20"><?= $row_buscatermo[0]['textotermo'] ?></textarea>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <label for="atualizatab" class="form-label">Cidade do termo</label>                               
                            <input type="text" class="form-control" placeholder="" value="<?= $row_buscatermo[0]['cidadetermo'] ?>" name="cidade"  />
                        </div>
                    </div>
                    

                    <div>
                        <button type="submit" class="btn btn-info btn-round waves-effect salvatermo">Salvar</button>
                        <input type="hidden" name="idtermo" value="<?= $row_buscatermo[0]['idtermo'] ?>">
                    </div>

                </form>
        
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#form-termo').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/termo-salvar.php', formAtual.serialize(), function(data){
                let jsonResponse = JSON.parse(data);
                if (jsonResponse.status == 1) {
                    swal({
                        title: "Termo de aceite",
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