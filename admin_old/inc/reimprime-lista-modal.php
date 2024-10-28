<!-- Large Size -->
<div class="modal fade" id="ModalParticipantesImprime" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="areaModalImprime">

             
            
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('body').on('click', '.reimprime', function(e) {
            e.preventDefault();
            // printAnotherDocument('comprovante.php', '#formImpressao');
            
            var prevenda = $(this).data('ticket');
            var tipo = $(this).data('tipo');

            $.ajax({
                method: "POST",
                url: './blocos/reimprime-comprovante.php',
                data: {idprevenda: prevenda, entradasaida: tipo},
                success: function(data) {
                    var printFrame = document.getElementById('printFrame');
                    var printFrameWindow = printFrame.contentWindow || printFrame;

                    printFrame.contentDocument.open();
                    printFrame.contentDocument.write(data);
                    printFrame.contentDocument.close();

                    /*
                    $(printFrameWindow).on('afterprint', function() {
                        window.location.href = './controle.php';
                    });
                    */

                    printFrameWindow.focus();
                    printFrameWindow.print();
                },
                error: function(data) {
                    alert('Falha ao carregar o documento para impressão.' + data);
                }
            });
            
        });

})
</script>


