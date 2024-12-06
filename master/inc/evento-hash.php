<?php
$sql_buscahash = "SELECT * FROM tbevento_ativo WHERE ativo=1 and idevento = ".$_GET['id'];
$pre_buscahash = $connPDO->prepare($sql_buscahash);
$pre_buscahash->execute();

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <h5>URL e QrCode</h5>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <label for="" class="form-label">Informe a URL a adicionar</label>                               
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="" value="" name=""  />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button type="submit" class="btn btn-info btn-round waves-effect">Adicionar</button>
                            <input type="hidden" name="evento" value="<?= $id ?>">
                        </div>

                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        if ($pre_buscahash->rowCount() < 1) {
                            echo "<h6>Nenhuma URL cadastrada</h6>";
                        } else {
                            $row_buscahash = $pre_buscahash->fetchAll(PDO::FETCH_ASSOC);
                        
                        ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="70%">URL</th>
                                        <th width="15%">Gerar QrCode</th>
                                        <th width="15%">Excluir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  foreach ($row_buscahash as $key => $value) { ?>
                                    <tr>
                                        <td>/<?= $value['hash'] ?></td>
                                        <td><a href="#" class="btn btn-sm btn-success ">Gerar</a></td>
                                        <td><a href="#" class="btn btn-sm btn-danger ">Excluir</a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>