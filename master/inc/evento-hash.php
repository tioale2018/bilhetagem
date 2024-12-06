<?php
// $sql_buscahash = "SELECT * FROM tbevento_ativo WHERE ativo=1 and idevento = ".$_GET['id'];
// $pre_buscahash = $connPDO->prepare($sql_buscahash);
// $pre_buscahash->execute();

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http";


?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <h5>URL e QrCode</h5>
                <hr>
                <form action="" method="post" id="formhash">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Informe a URL a adicionar</label>                               
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="" value="" name="urlhash"  />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <button type="submit" class="btn btn-info btn-round waves-effect bteventohash">Adicionar</button>
                                <input type="hidden" name="evento" value="<?= $id ?>">
                            </div>

                        </div>
                        
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive tabela-lista-hash">
        
                         </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="./assets/newplugins/qrcodejs/qrcode.min.js"></script>
<div id="qricode" style="display: none"></div>
<script type="text/javascript">

function gerarQRCode(url) {

    var qrcode = new QRCode(document.getElementById("qricode"), {
        text: url,
        width: 500,
        height: 500,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    const downloadQR = () => {
        let link = document.createElement('a');
        link.download = 'qrcode.png';
        link.href = document.querySelector('#qricode canvas').toDataURL()
        link.click();
    }

    downloadQR();
    document.getElementById("qricode").innerHTML = "";
}
    


</script>
<script>
    $(document).ready(function () {
        $('.tabela-lista-hash').load('./blocos/evento-hash-lista.php', {id: '<?= $id ?>'});

        $('#formhash').submit(function(e){
            e.preventDefault();
            let formAtual = $(this);
            $.post('./blocos/evento-hash-atualiza.php', formAtual.serialize(), function(data){
                $('.tabela-lista-hash').load('./blocos/evento-hash-lista.php', {id: '<?= $id ?>'});
                formAtual[0].reset();
            });
        });

        $('body').on('click', '.btnGeraqrcode', function(e){
            e.preventDefault();
            let url = $(this).attr('data-hash');
            // alert(url);
            gerarQRCode(url);
        });

        $('body').on('click', '.btnExcluihash', function(e){
            e.preventDefault();
            let url = $(this).attr('data-hash');

            swal({
                title: "Excluir URL?",
                text: "Esta ação não pode ser revertida! Deseja realmente excluir?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, excluir",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.post('./blocos/evento-hash-exclui.php', {hash:url}, function(data){
                        $('.tabela-lista-hash').load('./blocos/evento-hash-lista.php', {id: '<?= $id ?>'});
                    });
                }                
            });
        });
    });

</script>