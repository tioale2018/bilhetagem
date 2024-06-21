function printAnotherDocument(documento, formulario) {
    let Form = $(formulario).serialize();

    $.ajax({
        method: "POST",
        url: documento,
        data: Form,
        success: function(data) {
            var printFrame = document.getElementById('printFrame');
            var printFrameWindow = printFrame.contentWindow || printFrame;

            printFrame.contentDocument.open();
            printFrame.contentDocument.write(data);
            printFrame.contentDocument.close();

            $(printFrameWindow).on('afterprint', function() {
                window.location.href = 'controle.php';
            });

            printFrameWindow.focus();
            printFrameWindow.print();
        },
        error: function(data) {
            alert('Falha ao carregar o documento para impressão.' + data);
        }
    });
} 